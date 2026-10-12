<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class AdminProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index');
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'categoryOptions' => $this->buildCategoryOptions(),
            'brands' => Brand::active()->orderBy('name')->get(),
            'sizes' => Size::orderBy('id')->get(),
            'colors' => Color::orderBy('name')->get(),
            'selectedSizes' => [],
            'selectedColors' => [],
            'initialVariants' => [],
            'isEdit' => false,
        ]);
    }

    // TAO_SAN_PHAM (Hàm tạo mới sản phẩm và biến thể)
    // Hàm này nhận dữ liệu từ Form thêm mới sản phẩm và lưu vào Database
    public function store(StoreProductRequest $request): RedirectResponse
    {
        // Sử dụng DB::transaction để đảm bảo an toàn: 
        // Nếu đang lưu dở mà bị lỗi (ví dụ lỗi mạng, lỗi ổ cứng) thì sẽ hoàn tác (rollback) toàn bộ, không bị lưu thiếu dữ liệu.
        DB::transaction(function () use ($request) {
            // 1. Kiểm tra tính hợp lệ của dữ liệu đầu vào
            $data = $request->validated();

            // 2. Tạo bản ghi Sản Phẩm gốc (lưu thông tin chung như Tên, Giá, Danh mục, Thương hiệu...)
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?: Str::slug($data['name']) . '-' . Str::lower(Str::random(4)),
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'gender' => $data['gender'] ?? null,
                'sport' => $data['sport'] ?? null,
                'purpose' => $data['purpose'] ?? null,
                'style' => $data['style'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'sku' => $data['sku'] ?: $this->generateSku($data['name']),
                'is_featured' => $data['is_featured'],
                'is_active' => $data['is_active'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            // 3. Xử lý đồng bộ các biến thể (Màu sắc, Kích cỡ, Tồn kho riêng)
            $this->syncVariants($product, $data['variants']);

            // 4. Xử lý upload và nén hình ảnh
            $uploadedImages = $request->file('images', []);
            $imageColors = $request->input('image_colors', []);
            if (!empty($uploadedImages)) {
                $this->storeProductImages($product, $uploadedImages, $imageColors);
            }
        });

        // 5. Quay về trang danh sách và báo thành công
        return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images.color', 'variants']);

        $initialVariants = $product->variants->map(function ($variant) {
            return [
                'size_id' => (string) $variant->size_id,
                'color_id' => (string) $variant->color_id,
                'stock_quantity' => (int) $variant->stock_quantity,
                'additional_price' => (float) $variant->additional_price,
            ];
        })->values()->all();

        $selectedSizes = collect($initialVariants)->pluck('size_id')->unique()->values()->all();
        $selectedColors = collect($initialVariants)->pluck('color_id')->unique()->values()->all();

        $returnUrl = route('admin.products.index');
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        $indexPath = parse_url(route('admin.products.index'), PHP_URL_PATH);
        
        if ($previousUrl !== url()->current() && isset($parsedUrl['path']) && $parsedUrl['path'] === $indexPath) {
            session()->put('product_list_return_url', $previousUrl);
            $returnUrl = $previousUrl;
        } elseif (session()->has('product_list_return_url')) {
            $returnUrl = session()->get('product_list_return_url');
        }

        return view('admin.products.edit', [
            'product' => $product,
            'categoryOptions' => $this->buildCategoryOptions(),
            'brands' => Brand::active()->orderBy('name')->get(),
            'sizes' => Size::orderBy('id')->get(),
            'colors' => Color::orderBy('name')->get(),
            'selectedSizes' => $selectedSizes,
            'selectedColors' => $selectedColors,
            'initialVariants' => $initialVariants,
            'isEdit' => true,
            'returnUrl' => $returnUrl,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $data = $request->validated();

            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'] ?: Str::slug($data['name']) . '-' . Str::lower(Str::random(4)),
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'gender' => $data['gender'] ?? null,
                'sport' => $data['sport'] ?? null,
                'purpose' => $data['purpose'] ?? null,
                'style' => $data['style'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'sku' => $data['sku'] ?: $this->generateSku($data['name']),
                'is_featured' => $data['is_featured'],
                'is_active' => $data['is_active'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            $this->syncVariants($product, $data['variants']);

            $deleteIds = Arr::wrap($data['delete_existing_images'] ?? []);
            if (!empty($deleteIds)) {
                $this->deleteImagesByIds($product, $deleteIds);
            }

            $uploadedImages = $request->file('images', []);
            $imageColors = $request->input('image_colors', []);
            if (!empty($uploadedImages)) {
                $this->storeProductImages($product, $uploadedImages, $imageColors);
            }

            // Update existing image colors
            $existingImageColors = $request->input('existing_image_colors', []);
            foreach ($existingImageColors as $imageId => $colorId) {
                $img = $product->images()->find($imageId);
                if ($img) {
                    $img->update(['color_id' => $colorId ?: null]);
                }
            }

            if (!empty($data['primary_image_id'])) {
                $primary = $product->images()->where('id', $data['primary_image_id'])->first();
                if ($primary) {
                    $primary->makePrimary();
                }
            }
        });

        $returnUrl = session()->get('product_list_return_url', route('admin.products.index'));
        return redirect($returnUrl)->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                $this->deleteImageFiles($image->image_path);
            }

            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công.');
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $unique = [];
        $cleanRows = [];

        foreach ($variants as $row) {
            $key = $row['size_id'] . '-' . $row['color_id'];
            if (isset($unique[$key])) {
                continue;
            }

            $unique[$key] = true;
            $cleanRows[] = [
                'size_id' => $row['size_id'],
                'color_id' => $row['color_id'],
                'stock_quantity' => (int) ($row['stock_quantity'] ?? 0),
                'additional_price' => (float) ($row['additional_price'] ?? 0),
            ];
        }

        $product->variants()->delete();
        $product->variants()->createMany($cleanRows);
    }

    /**
     * @param array<int, UploadedFile> $files
     */
    private function storeProductImages(Product $product, array $files, array $imageColors = []): void
    {
        $manager = $this->resolveImageManager();
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $sortOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($files as $idx => $file) {
            $baseName = Str::uuid()->toString();
            if ($manager) {
                $imagePath = "products/{$baseName}.jpg";
                $thumbPath = "products/thumbs/{$baseName}_thumb.jpg";

                $image = $manager->read($file->getRealPath())->cover(800, 800);
                $thumb = $manager->read($file->getRealPath())->cover(400, 400);

                Storage::disk('public')->put($imagePath, (string) $image->toJpeg(85));
                Storage::disk('public')->put($thumbPath, (string) $thumb->toJpeg(80));
            } else {
                $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    $extension = 'jpg';
                }

                $imageFileName = "{$baseName}.{$extension}";
                $thumbFileName = "{$baseName}_thumb.{$extension}";
                $imagePath = "products/{$imageFileName}";
                $thumbPath = "products/thumbs/{$thumbFileName}";

                Storage::disk('public')->putFileAs('products', $file, $imageFileName);
                Storage::disk('public')->copy($imagePath, $thumbPath);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'color_id' => !empty($imageColors[$idx]) ? (int) $imageColors[$idx] : null,
                'image_path' => $imagePath,
                'sort_order' => $sortOrder++,
                'is_primary' => !$hasPrimary,
            ]);

            $hasPrimary = true;
        }
    }

    /**
     * @param array<int, int|string> $imageIds
     */
    private function deleteImagesByIds(Product $product, array $imageIds): void
    {
        $images = $product->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            $this->deleteImageFiles($image->image_path);
            $image->delete();
        }

        if (!$product->images()->where('is_primary', true)->exists()) {
            $first = $product->images()->orderBy('sort_order')->first();
            if ($first) {
                $first->makePrimary();
            }
        }
    }

    private function deleteImageFiles(string $imagePath): void
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'] ?? 'products';
        $fileName = $pathInfo['filename'] ?? '';
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
        $thumbPath = $directory . '/thumbs/' . $fileName . '_thumb' . $extension;

        Storage::disk('public')->delete([$imagePath, $thumbPath]);
    }

    private function resolveImageManager(): ?ImageManager
    {
        if (extension_loaded('gd')) {
            return new ImageManager(new GdDriver());
        }

        if (extension_loaded('imagick')) {
            return new ImageManager(new ImagickDriver());
        }

        return null;
    }

    private function generateSku(string $name): string
    {
        return strtoupper(Str::substr(Str::slug($name, ''), 0, 6)) . '-' . strtoupper(Str::random(6));
    }

    private function buildCategoryOptions(): array
    {
        $roots = Category::with(['children' => function ($query) {
            $query->orderBy('sort_order')->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($roots as $root) {
            $this->pushCategoryNode($root, $result, 0);
        }

        return $result;
    }

    private function pushCategoryNode(Category $node, array &$result, int $depth): void
    {
        $prefix = str_repeat('-- ', $depth);
        $result[] = [
            'id' => $node->id,
            'name' => $prefix . $node->name,
        ];

        foreach ($node->children as $child) {
            $child->loadMissing('children');
            $this->pushCategoryNode($child, $result, $depth + 1);
        }
    }
}
