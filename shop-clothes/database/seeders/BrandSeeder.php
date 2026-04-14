<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'SportWear Pro',
            'ActiveMan',
            'RunFast',
            'GymCore',
            'StreetFit',
            'UrbanMotion',
        ];

        foreach ($brands as $index => $name) {
            $slug = Str::slug($name);
            $logoPath = 'brands/' . $slug . '.svg';

            $this->generateBrandLogo($name, $logoPath);

            Brand::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => 'Thương hiệu ' . $name,
                    'is_active' => true,
                    'logo' => $logoPath,
                ]
            );
        }
    }

    private function generateBrandLogo(string $brandName, string $path): void
    {
        $palette = $this->colorPalette($brandName);
        $initials = $this->initials($brandName);
        $safeName = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600" role="img" aria-label="{$safeName}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$palette['start']}" />
      <stop offset="100%" stop-color="{$palette['end']}" />
    </linearGradient>
  </defs>
  <rect width="600" height="600" rx="56" fill="url(#bg)"/>
  <circle cx="300" cy="250" r="120" fill="rgba(255,255,255,0.18)"/>
  <text x="300" y="278" text-anchor="middle" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" font-size="96" font-weight="700" letter-spacing="4">{$initials}</text>
  <text x="300" y="462" text-anchor="middle" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" font-size="36" font-weight="600">{$safeName}</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);
    }

    private function initials(string $brandName): string
    {
        return collect(preg_split('/\s+/', trim($brandName)) ?: [])
            ->filter()
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->take(3)
            ->implode('');
    }

    private function colorPalette(string $brandName): array
    {
        $palettes = [
            ['start' => '#0f172a', 'end' => '#1d4ed8'],
            ['start' => '#14532d', 'end' => '#16a34a'],
            ['start' => '#7c2d12', 'end' => '#ea580c'],
            ['start' => '#4c1d95', 'end' => '#7c3aed'],
            ['start' => '#0c4a6e', 'end' => '#0891b2'],
            ['start' => '#3f3f46', 'end' => '#52525b'],
        ];

        $index = abs(crc32(Str::lower($brandName))) % count($palettes);

        return $palettes[$index];
    }
}
