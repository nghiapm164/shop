from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN
from pptx.enum.shapes import MSO_SHAPE
import os
import glob

# Try to find some images
image_files = glob.glob('d:/Lu/shop/shop-clothes/ppt_media/word/media/image*.png') + \
              glob.glob('d:/Lu/shop/shop-clothes/ppt_media/word/media/image*.jpeg') + \
              glob.glob('d:/Lu/shop/shop-clothes/ppt_media/word/media/image*.jpg')

def get_image(index):
    if index < len(image_files):
        return image_files[index]
    return None

prs = Presentation()
# Set slide size to Widescreen 16:9
prs.slide_width = Inches(13.333)
prs.slide_height = Inches(7.5)

# Colors
BRAND_INK = RGBColor(15, 23, 42) # Dark Navy
BRAND_PRIMARY = RGBColor(255, 99, 71) # Tomato/Orange
WHITE = RGBColor(255, 255, 255)
TEXT_GRAY = RGBColor(71, 85, 105)

def add_modern_background(slide, title_text, is_title_slide=False):
    # Add a top header bar
    if not is_title_slide:
        # Header shape
        header = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0), Inches(0), prs.slide_width, Inches(1.2))
        header.fill.solid()
        header.fill.fore_color.rgb = BRAND_INK
        header.line.color.rgb = BRAND_INK
        
        # Accent line
        accent = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0), Inches(1.2), prs.slide_width, Inches(0.08))
        accent.fill.solid()
        accent.fill.fore_color.rgb = BRAND_PRIMARY
        accent.line.color.rgb = BRAND_PRIMARY
        
        # Add title text manually to ensure styling
        txBox = slide.shapes.add_textbox(Inches(0.5), Inches(0.15), prs.slide_width - Inches(1), Inches(0.9))
        tf = txBox.text_frame
        p = tf.paragraphs[0]
        p.text = title_text
        p.font.bold = True
        p.font.size = Pt(36)
        p.font.color.rgb = WHITE
        p.alignment = PP_ALIGN.LEFT
    else:
        # Full background for title slide
        bg = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0), Inches(0), prs.slide_width, prs.slide_height)
        bg.fill.solid()
        bg.fill.fore_color.rgb = BRAND_INK
        bg.line.color.rgb = BRAND_INK
        
        accent = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0), prs.slide_height - Inches(0.5), prs.slide_width, Inches(0.5))
        accent.fill.solid()
        accent.fill.fore_color.rgb = BRAND_PRIMARY
        accent.line.color.rgb = BRAND_PRIMARY

slides_data = [
    # [Title, Content, Type(0=Title, 1=Text, 2=ImageSplit)]
    ("XÂY DỰNG PHẦN MỀM WEBSITE\nBÁN QUẦN ÁO THỂ THAO NAM", "Sinh viên: Lê Tuấn Hùng, Trần Anh Đăng, Hoàng Văn Tú\nHọc viện Nông nghiệp Việt Nam - Khoa CNTT\nĐơn vị thực tập: Công ty Âu Lạc", 0),
    ("Nội dung chính", "1. Tổng quan dự án\n2. Công nghệ sử dụng\n3. Phân tích thiết kế hệ thống\n4. Kết quả triển khai\n5. Tổng kết & Hướng phát triển", 1),
    ("Tổng quan môi trường thực tập", "• Đơn vị: Công ty bảo hiểm bưu điện Âu Lạc\n• Lĩnh vực: Phát triển phần mềm, giải pháp chuyển đổi số\n• Quy trình làm việc: Agile/Scrum (Sprint 2 tuần)\n• Quản lý mã nguồn: Git/GitHub", 1),
    ("Đặt vấn đề & Mục tiêu", "THỰC TRẠNG:\n• Cửa hàng truyền thống khó quản lý hàng hóa đa dạng biến thể.\n• Tốc độ website cũ chậm, UX/UI kém trên thiết bị di động.\n\nMỤC TIÊU:\n• Xây dựng hệ thống TMĐT Mobile-First hiện đại.\n• Quản trị tồn kho và biến thể sản phẩm chặt chẽ.\n• Phân quyền linh hoạt bằng Role-Based Access Control.", 1),
    ("Kiến trúc hệ thống & Công nghệ", "KIẾN TRÚC:\n• Mô hình: MVC (Model - View - Controller)\n• Kiến trúc thành phần phản hồi (Reactive Component)\n\nCÔNG NGHỆ ÁP DỤNG:\n• Backend: Laravel, Livewire\n• Frontend: Tailwind CSS, JS (ES6+)\n• Cơ sở dữ liệu: MySQL", 1),
    ("Biểu đồ Use Case tổng quát", "Sơ đồ luồng chức năng của Khách hàng và Admin", 2),
    ("Thiết kế Cơ sở dữ liệu (ERD)", "Các bảng chính:\n• products, product_variants\n• orders, order_items\n• users, flash_sales", 2),
    ("Luồng xử lý cốt lõi (Đặt hàng)", "Quy trình từ lúc chọn sản phẩm đến khi thanh toán COD", 2),
    ("Demo - Giao diện Khách hàng", "Trang chủ, Danh mục, Bộ lọc sản phẩm", 2),
    ("Demo - Trải nghiệm Đặt hàng", "Chi tiết sản phẩm -> Giỏ hàng -> Thanh toán", 2),
    ("Demo - Quản lý Cá nhân", "Lịch sử đơn hàng, Hồ sơ người dùng", 2),
    ("Demo - Admin Dashboard", "Thống kê doanh thu, số lượng đơn hàng", 2),
    ("Demo - Admin Vận hành", "Quản lý sản phẩm, ma trận biến thể", 2),
    ("Đánh giá & Kiểm thử", "Các luồng đã test thành công:\n• Đăng nhập, phân quyền.\n• Thêm giỏ hàng.\n• Thanh toán COD.", 1),
    ("Kết luận & Hướng phát triển", "KẾT QUẢ:\n• Website load nhanh, UX/UI tốt.\n• Quản lý kho hàng chuyên nghiệp.\n\nHƯỚNG PHÁT TRIỂN:\n• Tích hợp VNPAY, MoMo.\n• AI gợi ý sản phẩm.", 1),
    ("Lời Cảm Ơn", "Chân thành cảm ơn Thầy Cô và Công ty Âu Lạc!\n\n---- Q&A ----", 0)
]

img_idx = 10 # Start at an offset to hopefully get some actual large charts/screenshots
for slide_info in slides_data:
    title_text = slide_info[0]
    content_text = slide_info[1]
    slide_type = slide_info[2]
    
    slide_layout = prs.slide_layouts[6] 
    slide = prs.slides.add_slide(slide_layout)
    
    if slide_type == 0:
        add_modern_background(slide, "", True)
        
        txBox = slide.shapes.add_textbox(Inches(1), Inches(2), prs.slide_width - Inches(2), Inches(2))
        tf = txBox.text_frame
        tf.word_wrap = True
        p = tf.paragraphs[0]
        p.text = title_text
        p.font.bold = True
        p.font.size = Pt(54)
        p.font.color.rgb = WHITE
        p.alignment = PP_ALIGN.CENTER
        
        txBox2 = slide.shapes.add_textbox(Inches(1), Inches(4.5), prs.slide_width - Inches(2), Inches(2))
        tf2 = txBox2.text_frame
        tf2.word_wrap = True
        p2 = tf2.paragraphs[0]
        p2.text = content_text
        p2.font.size = Pt(24)
        p2.font.color.rgb = RGBColor(200, 200, 200)
        p2.alignment = PP_ALIGN.CENTER
        
    elif slide_type == 1:
        add_modern_background(slide, title_text, False)
        
        txBox = slide.shapes.add_textbox(Inches(1), Inches(1.8), prs.slide_width - Inches(2), Inches(5))
        tf = txBox.text_frame
        tf.word_wrap = True
        
        paragraphs = content_text.split('\n')
        for i, para in enumerate(paragraphs):
            if i == 0:
                p = tf.paragraphs[0]
            else:
                p = tf.add_paragraph()
            p.text = para
            p.font.size = Pt(28)
            p.font.color.rgb = TEXT_GRAY
            
    elif slide_type == 2:
        add_modern_background(slide, title_text, False)
        
        txBox = slide.shapes.add_textbox(Inches(0.5), Inches(1.8), Inches(4.5), Inches(5))
        tf = txBox.text_frame
        tf.word_wrap = True
        
        paragraphs = content_text.split('\n')
        for i, para in enumerate(paragraphs):
            if i == 0:
                p = tf.paragraphs[0]
            else:
                p = tf.add_paragraph()
            p.text = para
            p.font.size = Pt(28)
            p.font.color.rgb = TEXT_GRAY
            
        img_path = get_image(img_idx)
        if img_path:
            try:
                # Try inserting the image. We'll set max height/width
                slide.shapes.add_picture(img_path, Inches(5), Inches(1.5), width=Inches(8))
                img_idx += 3 # skip icons, jump to next major image
            except:
                pass

prs.save("Bao_Cao_TTNN_Modern.pptx")
