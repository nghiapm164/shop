{{-- AI SPORT ASSISTANT – chatbot tư vấn sản phẩm (nút nổi góc dưới màn hình) --}}
<div id="msc-chatbot"
     data-endpoint="{{ route('chatbot.message') }}"
     data-reset="{{ route('chatbot.reset') }}"
     data-csrf="{{ csrf_token() }}"
     data-placeholder="{{ asset('images/product-placeholder.svg') }}"
     data-suggestions='@json(\App\Services\Chatbot\ChatbotService::suggestions())'>

    <button type="button" class="msc-fab" id="msc-fab" aria-label="Mở trợ lý AI MODESPORT" aria-controls="msc-panel" aria-expanded="false">
        <span class="msc-fab-ring" aria-hidden="true"></span>
        <span class="msc-fab-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.4 8.4 0 0 1-12.2 7.5L3.5 20.5l1.6-4.8A8.4 8.4 0 1 1 21 11.5z"/>
                <path d="M12.6 7.2l.9 2.1 2.1.9-2.1.9-.9 2.1-.9-2.1-2.1-.9 2.1-.9z" fill="currentColor" stroke="none"/>
            </svg>
        </span>
        <span class="msc-fab-label">Tư vấn AI</span>
    </button>

    <section class="msc-panel" id="msc-panel" role="dialog" aria-modal="false" aria-labelledby="msc-title" tabindex="-1" hidden>
        <header class="msc-header">
            <div class="msc-avatar" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2.5l2 5 5 2-5 2-2 5-2-5-5-2 5-2z"/><path d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9z" opacity=".7"/></svg>
                <i class="msc-online"></i>
            </div>
            <div class="msc-title">
                <strong id="msc-title">MODESPORT AI</strong>
                <span class="msc-status"><span class="msc-status-text">Đang hoạt động</span></span>
            </div>
            @if (config('chatbot.demo_mode'))
                <button type="button" class="msc-demo-btn" id="msc-demo-btn" aria-expanded="false" aria-controls="msc-demo" title="5 tình huống demo (Alt+1 … Alt+5)">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                    <span class="msc-demo-label">Demo</span>
                </button>
            @endif
            <button type="button" class="msc-icon-btn" id="msc-reset" title="Cuộc trò chuyện mới" aria-label="Cuộc trò chuyện mới">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            </button>
            <button type="button" class="msc-icon-btn" id="msc-close" title="Đóng" aria-label="Đóng khung chat">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </header>

        @if (config('chatbot.demo_mode'))
            <div class="msc-demo" id="msc-demo" hidden>
                <div class="msc-demo-head">
                    <strong>Tình huống demo bắt buộc</strong>
                    <span>Bấm để gửi · phím tắt Alt+1 … Alt+5</span>
                </div>
                <ol class="msc-demo-list">
                    @foreach (\App\Services\Chatbot\ChatbotService::demoCases() as $i => $case)
                        <li>
                            <button type="button" data-ask="{{ $case['question'] }}" data-demo="{{ $i + 1 }}">
                                <span class="msc-demo-no">{{ $i + 1 }}</span>
                                <span class="msc-demo-body">
                                    <span class="msc-demo-title">Demo {{ $i + 1 }} – {{ $case['title'] }}</span>
                                    <span class="msc-demo-q">“{{ $case['question'] }}”</span>
                                    <span class="msc-demo-expect">Kỳ vọng: {{ $case['expect'] }}</span>
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        <div class="msc-messages" id="msc-messages" role="log" aria-live="polite"></div>

        <div class="msc-footer">
            <div class="msc-samples-head">
                <span>Gợi ý cho bạn</span>
                <button type="button" id="msc-samples-toggle" aria-expanded="true" aria-controls="msc-suggestions">Thu gọn</button>
            </div>
            <div class="msc-suggestions" id="msc-suggestions" aria-label="Câu hỏi gợi ý"></div>
            <form class="msc-input" id="msc-form" autocomplete="off">
                <div class="msc-input-box">
                    <input type="text" id="msc-text" maxlength="500" enterkeyhint="send"
                           placeholder="Nhập câu hỏi của bạn..." aria-label="Nhập câu hỏi">
                    <button type="submit" id="msc-send" aria-label="Gửi">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M3.4 20.4 21.5 12 3.4 3.6 3.4 10l12.1 2-12.1 2z"/></svg>
                    </button>
                </div>
            </form>
            <p class="msc-note">Trợ lý AI tư vấn dựa trên dữ liệu sản phẩm của MODESPORT</p>
        </div>
    </section>
</div>

<style>
    #msc-chatbot {
        --msc-primary: #E42508; --msc-primary-dark: #b91c04; --msc-primary-soft: #fff1ee; --msc-primary-line: #fbd0c6;
        --msc-ink: #161922; --msc-text: #1f2430; --msc-muted: #6b7280; --msc-faint: #9ca3af;
        --msc-border: #e8eaee; --msc-bg: #f4f5f7; --msc-card: #ffffff;
        font-family: 'Archivo', 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    #msc-chatbot *, #msc-chatbot *::before, #msc-chatbot *::after { box-sizing: border-box; }
    #msc-chatbot [hidden] { display: none !important; }
    #msc-chatbot button { font-family: inherit; }

    /* ---------- Nút nổi ---------- */
    .msc-fab {
        position: fixed; right: 20px; bottom: 20px; z-index: 9998;
        display: inline-flex; align-items: center; gap: 10px;
        height: 56px; padding: 0 20px 0 8px; border: 0; border-radius: 999px;
        background: linear-gradient(135deg, #ff4a26 0%, var(--msc-primary) 55%, var(--msc-primary-dark) 100%);
        color: #fff; font-weight: 700; font-size: 15px; letter-spacing: .01em; cursor: pointer;
        box-shadow: 0 14px 30px -10px rgba(228, 37, 8, .65), inset 0 1px 0 rgba(255, 255, 255, .25);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .msc-fab:hover { transform: translateY(-2px); box-shadow: 0 18px 34px -10px rgba(228, 37, 8, .75); }
    .msc-fab:focus-visible { outline: 3px solid rgba(228, 37, 8, .35); outline-offset: 3px; }
    .msc-fab-icon { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; background: rgba(255, 255, 255, .18); }
    .msc-fab-ring { position: absolute; inset: 0; border-radius: inherit; pointer-events: none; animation: msc-ring 2.6s ease-out infinite; }
    @keyframes msc-ring { 0% { box-shadow: 0 0 0 0 rgba(228, 37, 8, .45); } 70%, 100% { box-shadow: 0 0 0 14px rgba(228, 37, 8, 0); } }
    .msc-fab.is-open { display: none; }

    /* ---------- Khung chat: sidebar bên phải (desktop) ---------- */
    .msc-panel {
        outline: none;
        position: fixed; top: 0; right: 0; bottom: 0; z-index: 9999;
        width: clamp(400px, 32vw, 480px); height: 100vh; height: 100dvh;
        display: flex; flex-direction: column; overflow: hidden;
        background: var(--msc-bg); color: var(--msc-text);
        border-left: 1px solid rgba(16, 20, 30, .08); box-shadow: -20px 0 50px -18px rgba(16, 20, 30, .35);
        animation: msc-in .28s cubic-bezier(.2, .8, .2, 1);
    }
    @keyframes msc-in { from { opacity: 0; transform: translateX(36px); } to { opacity: 1; transform: none; } }

    /* Header màu thương hiệu */
    .msc-header {
        position: relative; flex: none; display: flex; align-items: center; gap: 10px;
        padding: 14px 8px 14px 16px; color: #fff;
        background:
            radial-gradient(140% 120% at 100% 0%, rgba(255, 255, 255, .16) 0%, rgba(255, 255, 255, 0) 55%),
            linear-gradient(135deg, #ff4a26 0%, var(--msc-primary) 50%, var(--msc-primary-dark) 100%);
        box-shadow: 0 6px 18px -12px rgba(185, 28, 4, .9);
    }
    .msc-avatar {
        position: relative; flex: none; width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center;
        color: var(--msc-primary); background: #fff; box-shadow: 0 6px 14px -6px rgba(0, 0, 0, .35);
    }
    .msc-online { position: absolute; right: 0; bottom: 1px; width: 11px; height: 11px; border-radius: 50%; background: #22c55e; border: 2px solid #fff; }
    .msc-title { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 1px; }
    .msc-title strong { font-size: 16px; font-weight: 800; letter-spacing: .01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-status { font-size: 12px; color: rgba(255, 255, 255, .85); display: flex; min-width: 0; }
    .msc-status-text { min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-icon-btn {
        flex: none; width: 40px; height: 40px; display: grid; place-items: center;
        border: 0; border-radius: 50%; background: transparent; color: rgba(255, 255, 255, .9); cursor: pointer; transition: background .15s;
    }
    .msc-icon-btn:hover, .msc-icon-btn:focus-visible { background: rgba(255, 255, 255, .18); color: #fff; outline: none; }

    /* ---------- Nút & bảng Demo (5 tình huống bắt buộc) ---------- */
    .msc-demo-btn {
        flex: none; display: inline-flex; align-items: center; gap: 5px; height: 30px; padding: 0 11px; border-radius: 999px; cursor: pointer;
        border: 1px solid rgba(255, 255, 255, .45); background: rgba(255, 255, 255, .14); color: #fff; font-size: 12px; font-weight: 800; letter-spacing: .03em;
        transition: background .15s, color .15s;
    }
    .msc-demo-btn:hover, .msc-demo-btn[aria-expanded="true"] { background: #fff; color: var(--msc-primary); }
    .msc-demo {
        flex: none; max-height: 62%; overflow-y: auto; overscroll-behavior: contain;
        background: #fff; border-bottom: 1px solid var(--msc-border); box-shadow: 0 12px 26px -20px rgba(16, 20, 30, .7);
        animation: msc-drop .18s ease-out;
    }
    @keyframes msc-drop { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }
    .msc-demo-head { display: flex; flex-direction: column; gap: 2px; padding: 12px 16px 6px; }
    .msc-demo-head strong { font-size: 13.5px; font-weight: 800; }
    .msc-demo-head span { font-size: 11.5px; color: var(--msc-muted); }
    #msc-chatbot .msc-demo-list { list-style: none; margin: 0; padding: 4px 12px 12px; display: flex; flex-direction: column; gap: 6px; }
    .msc-demo-list button {
        width: 100%; display: flex; gap: 10px; align-items: flex-start; text-align: left; cursor: pointer;
        padding: 10px 12px; border-radius: 12px; border: 1px solid var(--msc-border); background: #fafafb; color: var(--msc-text);
        transition: border-color .15s, background .15s;
    }
    .msc-demo-list button:hover, .msc-demo-list button:focus-visible { border-color: var(--msc-primary-line); background: var(--msc-primary-soft); outline: none; }
    .msc-demo-no { flex: none; width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; background: var(--msc-primary); color: #fff; font-weight: 800; font-size: 12.5px; }
    .msc-demo-body { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .msc-demo-title { font-size: 11px; font-weight: 800; color: var(--msc-primary); text-transform: uppercase; letter-spacing: .04em; }
    .msc-demo-q { font-size: 13.5px; font-weight: 600; line-height: 1.4; }
    .msc-demo-expect { font-size: 11.5px; color: var(--msc-muted); }

    /* ---------- Vùng hội thoại ---------- */
    .msc-messages {
        position: relative;
        flex: 1; min-height: 0; overflow-y: auto; overscroll-behavior: contain; -webkit-overflow-scrolling: touch;
        padding: 16px 16px 14px; display: flex; flex-direction: column; gap: 16px; scroll-behavior: smooth;
    }
    .msc-messages::-webkit-scrollbar { width: 6px; }
    .msc-messages::-webkit-scrollbar-thumb { background: #d3d7de; border-radius: 3px; }

    /* Thẻ chào */
    .msc-hero { text-align: center; background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 18px; padding: 20px 18px 16px; box-shadow: 0 6px 18px -16px rgba(16, 20, 30, .5); }
    .msc-hero-avatar {
        width: 54px; height: 54px; margin: 0 auto 10px; border-radius: 50%; display: grid; place-items: center; color: #fff;
        background: linear-gradient(135deg, #ff4a26, var(--msc-primary)); box-shadow: 0 10px 22px -10px rgba(228, 37, 8, .8);
    }
    .msc-hero h3 { margin: 0 0 4px; font-size: 17px; font-weight: 800; color: var(--msc-ink); }
    .msc-hero p { margin: 0 auto; max-width: 300px; font-size: 13.5px; line-height: 1.5; color: var(--msc-muted); }
    .msc-skills { display: flex; flex-wrap: wrap; justify-content: center; gap: 6px; margin-top: 14px; }
    .msc-skills span { font-size: 12px; font-weight: 600; padding: 5px 10px; border-radius: 999px; background: var(--msc-bg); color: #374151; }

    .msc-divider { display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 700; color: var(--msc-faint); text-transform: uppercase; letter-spacing: .06em; }
    .msc-divider::before, .msc-divider::after { content: ''; flex: 1; height: 1px; background: #e2e5ea; }

    /* Hàng tin nhắn */
    .msc-row { display: flex; gap: 8px; min-width: 0; max-width: 100%; }
    .msc-row.is-user { justify-content: flex-end; }
    .msc-row.is-new { animation: msc-pop .28s ease-out; }
    @keyframes msc-pop { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
    .msc-mini-avatar {
        flex: none; width: 30px; height: 30px; border-radius: 50%; display: grid; place-items: center; margin-top: 18px;
        color: #fff; background: linear-gradient(135deg, #ff4a26, var(--msc-primary));
    }
    .msc-col { display: flex; flex-direction: column; gap: 8px; min-width: 0; flex: 1; }
    .msc-row.is-user .msc-col { align-items: flex-end; }
    .msc-sender { font-size: 11.5px; font-weight: 700; color: var(--msc-muted); margin: 0 0 -4px 2px; }
    .msc-time { font-size: 10.5px; color: var(--msc-faint); margin: -3px 4px 0; }

    .msc-bubble { max-width: 100%; padding: 10px 14px; border-radius: 18px; font-size: 14.5px; line-height: 1.55; overflow-wrap: anywhere; }
    .msc-row.is-user .msc-bubble { max-width: 84%; background: var(--msc-primary); color: #fff; border-bottom-right-radius: 6px; box-shadow: 0 8px 18px -12px rgba(228, 37, 8, .9); }
    .msc-row.is-bot .msc-bubble { align-self: flex-start; background: var(--msc-card); border: 1px solid var(--msc-border); border-top-left-radius: 6px; box-shadow: 0 4px 12px -10px rgba(16, 20, 30, .35); }
    .msc-bubble strong { font-weight: 700; color: inherit; }
    .msc-bubble p { margin: 0; }
    .msc-bubble p + p, .msc-bubble p + ul, .msc-bubble p + ol, .msc-bubble ul + p, .msc-bubble ol + p { margin-top: 6px; }
    #msc-chatbot .msc-bubble ul, #msc-chatbot .msc-bubble ol { margin: 4px 0 0; padding-left: 20px; }
    #msc-chatbot .msc-bubble ul { list-style: disc outside; }
    #msc-chatbot .msc-bubble ol { list-style: decimal outside; }
    #msc-chatbot .msc-bubble li { display: list-item; margin: 3px 0; }
    .msc-bubble li::marker { color: var(--msc-primary); }

    /* AI hiểu */
    .msc-analysis { display: flex; flex-wrap: wrap; align-items: center; gap: 5px; padding: 8px 10px; border-radius: 12px; background: #eef2f7; }
    .msc-analysis-label { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: .05em; margin-right: 2px; }
    .msc-tag { font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 999px; background: #fff; color: #334155; border: 1px solid #dde3ea; white-space: nowrap; }

    /* Thẻ sản phẩm */
    .msc-cards { display: flex; gap: 10px; overflow-x: auto; overscroll-behavior-x: contain; padding: 2px 2px 8px; scroll-snap-type: x mandatory; scrollbar-width: thin; }
    .msc-cards::-webkit-scrollbar { height: 5px; }
    .msc-cards::-webkit-scrollbar-thumb { background: #d3d7de; border-radius: 3px; }
    .msc-card {
        flex: none; width: 150px; display: flex; flex-direction: column; overflow: hidden; scroll-snap-align: start;
        background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 14px;
        text-decoration: none !important; color: inherit !important; transition: transform .18s, box-shadow .18s, border-color .18s;
    }
    .msc-card:hover { transform: translateY(-2px); border-color: #dcdfe5; box-shadow: 0 14px 26px -16px rgba(16, 20, 30, .5); }
    .msc-card-img { position: relative; aspect-ratio: 1 / 1; background: #eceef2; overflow: hidden; }
    .msc-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s; }
    .msc-card:hover .msc-card-img img { transform: scale(1.04); }
    .msc-badge { position: absolute; top: 8px; left: 8px; background: var(--msc-primary); color: #fff; font-size: 11px; font-weight: 800; padding: 2px 7px; border-radius: 6px; }
    .msc-card-body { flex: 1; display: flex; flex-direction: column; gap: 4px; padding: 9px 10px 10px; }
    .msc-card-cat { font-size: 10.5px; font-weight: 700; color: var(--msc-faint); text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-card-name { font-size: 13px; font-weight: 600; line-height: 1.35; min-height: 35px; color: var(--msc-ink); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .msc-price { display: flex; align-items: baseline; flex-wrap: wrap; gap: 0 6px; margin-top: 2px; }
    .msc-price b { color: var(--msc-primary); font-size: 15px; font-weight: 800; }
    .msc-price s { color: var(--msc-faint); font-size: 11.5px; }
    .msc-meta { display: flex; align-items: center; justify-content: space-between; gap: 6px; font-size: 11px; color: var(--msc-muted); }
    .msc-swatches { display: flex; gap: 3px; }
    .msc-swatch { width: 12px; height: 12px; border-radius: 50%; box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .15); }
    .msc-sizes { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-card-btn {
        margin-top: 6px; display: flex; align-items: center; justify-content: center; gap: 4px;
        font-size: 12.5px; font-weight: 700; padding: 7px 0; border-radius: 9px;
        background: var(--msc-primary-soft); color: var(--msc-primary); transition: background .15s, color .15s;
    }
    .msc-card:hover .msc-card-btn { background: var(--msc-primary); color: #fff; }

    /* Bộ đồ */
    .msc-outfit { background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 16px; padding: 10px; min-width: 0; box-shadow: 0 4px 12px -10px rgba(16, 20, 30, .35); }
    .msc-outfit-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin: 0 2px 8px; font-size: 13.5px; }
    .msc-outfit-head strong { font-weight: 800; color: var(--msc-ink); }
    .msc-total { font-size: 12.5px; font-weight: 800; color: #fff; background: var(--msc-primary); padding: 4px 10px; border-radius: 999px; white-space: nowrap; }
    .msc-outfit-items { display: grid; grid-template-columns: 1fr auto 1fr; align-items: stretch; gap: 6px; }
    .msc-outfit-items .msc-card { width: auto; min-width: 0; box-shadow: none; }
    .msc-plus { align-self: center; width: 22px; height: 22px; border-radius: 50%; display: grid; place-items: center; background: var(--msc-bg); color: var(--msc-muted); font-weight: 800; font-size: 14px; }

    .msc-links { display: flex; flex-wrap: wrap; gap: 6px; }
    .msc-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 700; padding: 7px 12px; border-radius: 999px; border: 1px solid var(--msc-primary-line); color: var(--msc-primary) !important; background: #fff; text-decoration: none !important; }
    .msc-link:hover { background: var(--msc-primary); border-color: var(--msc-primary); color: #fff !important; }
    .msc-source { display: inline-flex; align-items: center; gap: 4px; align-self: flex-start; font-size: 10.5px; font-weight: 600; color: var(--msc-faint); }

    /* Trạng thái "AI đang làm việc" */
    .msc-row.is-thinking .msc-mini-avatar { animation: msc-glow 1.6s ease-in-out infinite; }
    @keyframes msc-glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(228, 37, 8, .45); } 50% { box-shadow: 0 0 0 7px rgba(228, 37, 8, 0); } }
    .msc-thinking { display: flex; flex-direction: column; gap: 10px; align-self: stretch; }
    .msc-think-line {
        display: inline-flex; align-items: center; gap: 10px; align-self: flex-start; padding: 10px 16px 10px 12px;
        background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 18px; border-top-left-radius: 6px;
        box-shadow: 0 4px 12px -10px rgba(16, 20, 30, .35);
    }
    .msc-orb {
        position: relative; flex: none; width: 18px; height: 18px; border-radius: 50%;
        background: conic-gradient(from 0deg, #ff7a59, var(--msc-primary), #ffb199, #ff7a59);
        animation: msc-spin 1.1s linear infinite;
    }
    .msc-orb::after { content: ''; position: absolute; inset: 4px; border-radius: 50%; background: #fff; }
    @keyframes msc-spin { to { transform: rotate(360deg); } }
    .msc-think-text {
        font-size: 13px; font-weight: 600;
        background: linear-gradient(90deg, #8a919e 0%, #8a919e 35%, var(--msc-primary) 50%, #8a919e 65%, #8a919e 100%);
        background-size: 250% 100%; -webkit-background-clip: text; background-clip: text; color: transparent;
        animation: msc-shine 1.8s linear infinite;
    }
    .msc-think-text.is-swap { animation: msc-shine 1.8s linear infinite, msc-fade .35s ease-out; }
    @keyframes msc-shine { from { background-position: 100% 0; } to { background-position: -150% 0; } }
    @keyframes msc-fade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
    .msc-skeletons { display: flex; gap: 10px; overflow: hidden; }
    .msc-skel { flex: none; width: 150px; border-radius: 14px; background: var(--msc-card); border: 1px solid var(--msc-border); overflow: hidden; animation: msc-pop .4s ease-out both; }
    .msc-skel:nth-child(2) { animation-delay: .12s; } .msc-skel:nth-child(3) { animation-delay: .24s; }
    .msc-skel-img, .msc-skel-line { position: relative; overflow: hidden; background: #eceef2; }
    .msc-skel-img { aspect-ratio: 1 / 1; }
    .msc-skel-line { height: 9px; border-radius: 5px; margin: 10px 10px 0; }
    .msc-skel-line.short { width: 45%; margin-bottom: 12px; }
    .msc-skel-img::after, .msc-skel-line::after {
        content: ''; position: absolute; inset: 0; transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .75), transparent);
        animation: msc-shimmer 1.3s ease-in-out infinite;
    }
    @keyframes msc-shimmer { to { transform: translateX(100%); } }

    /* ---------- Chân khung chat ---------- */
    .msc-footer { flex: none; background: #fff; border-top: 1px solid var(--msc-border); padding-bottom: env(safe-area-inset-bottom, 0); }
    .msc-samples-head { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px 0 16px; }
    .msc-samples-head span { font-size: 11px; font-weight: 800; color: var(--msc-muted); text-transform: uppercase; letter-spacing: .06em; }
    .msc-samples-head button { border: 0; background: none; padding: 6px 4px; font-size: 12px; font-weight: 700; color: var(--msc-primary); cursor: pointer; }
    .msc-samples-head button::after { content: ' ▾'; }
    .msc-samples-head button[aria-expanded="false"]::after { content: ' ▴'; }
    .msc-suggestions {
        display: flex; flex-direction: column; gap: 6px;
        max-height: 38vh; max-height: 38dvh; overflow-y: auto; overscroll-behavior: contain;
        padding: 6px 12px 4px; scrollbar-width: thin; scrollbar-color: #d5d9e0 transparent;
    }
    .msc-suggestions::-webkit-scrollbar { width: 5px; }
    .msc-suggestions::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 3px; }
    .msc-suggestions:empty, .msc-suggestions.is-collapsed { display: none; }
    .msc-suggestions button {
        flex: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%; min-height: 40px;
        text-align: left; font-size: 13px; line-height: 1.4; padding: 8px 12px; border-radius: 12px; white-space: normal; cursor: pointer;
        border: 1px solid var(--msc-border); background: #fafafb; color: #2f3542; transition: border-color .15s, background .15s, color .15s;
    }
    .msc-suggestions button::after { content: '↗'; flex: none; font-size: 13px; color: var(--msc-faint); transition: color .15s; }
    .msc-suggestions button:hover { border-color: var(--msc-primary-line); color: var(--msc-primary); background: var(--msc-primary-soft); }
    .msc-suggestions button:hover::after { color: var(--msc-primary); }
    .msc-suggestions button.is-followup { background: #fff; border-style: dashed; }

    .msc-input { margin: 0; padding: 10px 12px 4px; }
    .msc-input-box {
        display: flex; align-items: center; gap: 6px; padding: 4px 4px 4px 16px;
        background: var(--msc-bg); border: 1px solid transparent; border-radius: 999px; transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .msc-input-box:focus-within { background: #fff; border-color: #f2a491; box-shadow: 0 0 0 4px rgba(228, 37, 8, .1); }
    .msc-input input {
        flex: 1; min-width: 0; height: 40px; padding: 0; margin: 0; border: 0; outline: none; box-shadow: none; background: transparent;
        font-family: inherit; font-size: 15px; color: var(--msc-text);
    }
    .msc-input input:focus { outline: none; box-shadow: none; }
    .msc-input input::placeholder { color: var(--msc-faint); }
    .msc-input button {
        flex: none; width: 40px; height: 40px; border: 0; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
        color: #fff; background: var(--msc-primary); box-shadow: 0 8px 16px -8px rgba(228, 37, 8, .9); transition: transform .15s, background .15s, opacity .15s;
    }
    .msc-input button:hover { background: var(--msc-primary-dark); transform: scale(1.05); }
    .msc-input button:disabled { opacity: .45; cursor: not-allowed; transform: none; }
    .msc-note { margin: 0; padding: 4px 12px 9px; font-size: 10.5px; color: var(--msc-faint); text-align: center; }

    /* ---------- Mobile: toàn màn hình ---------- */
    @media (max-width: 640px), (max-height: 500px) {
        .msc-fab { right: 14px; bottom: calc(14px + env(safe-area-inset-bottom, 0px)); height: 56px; width: 56px; padding: 0; justify-content: center; }
        .msc-fab-icon { background: transparent; }
        .msc-fab-label { display: none; }
        .msc-panel {
            inset: 0; right: auto; bottom: auto; width: 100%; height: 100%; height: 100dvh; max-height: none;
            border: 0; border-radius: 0; box-shadow: none; animation-name: msc-in-mobile;
        }
        @keyframes msc-in-mobile { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        .msc-header { padding-top: calc(12px + env(safe-area-inset-top, 0px)); }
        .msc-messages { padding: 14px 12px 10px; }
        .msc-card { width: 142px; }
        .msc-input input { font-size: 16px; } /* >= 16px để iPhone không tự phóng to khi gõ */
    }
    @media (max-width: 640px) {
        body { padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px)); } /* chừa chỗ cho nút chat nổi */
    }
    /* Màn hình rất thấp (xoay ngang / bàn phím ảo đang mở): thu gọn phần đầu & chân */
    @media (max-height: 500px) {
        .msc-note, .msc-suggestions, .msc-samples-head, .msc-hero { display: none; }
        .msc-header { padding-top: 6px; padding-bottom: 6px; }
        .msc-avatar { width: 32px; height: 32px; }
        .msc-status { display: none; }
        .msc-input { padding: 6px 12px; }
    }
    @media (max-width: 480px) {
        .msc-demo-btn { width: 32px; height: 32px; padding: 0; justify-content: center; }
        .msc-demo-label { display: none; }
    }
    @media (max-width: 360px) {
        .msc-card, .msc-skel { width: 132px; }
        .msc-row.is-bot .msc-mini-avatar { display: none; }
        .msc-messages { padding-left: 10px; padding-right: 10px; }
        .msc-outfit { padding: 8px; }
        .msc-outfit-items { gap: 4px; }
        .msc-plus { width: 18px; height: 18px; font-size: 12px; }
        .msc-outfit-items .msc-card-body { padding: 8px; }
        .msc-outfit-items .msc-sizes { display: none; }
        .msc-outfit-items .msc-card-btn { font-size: 12px; }
        .msc-header { gap: 6px; padding-left: 12px; }
    }
    html.msc-lock, html.msc-lock body { overflow: hidden !important; }
    @media (prefers-reduced-motion: reduce) {
        .msc-fab-ring, .msc-panel, .msc-row.is-new, .msc-orb, .msc-think-text, .msc-skel, .msc-skel-img::after, .msc-skel-line::after,
        .msc-row.is-thinking .msc-mini-avatar { animation: none !important; }
    }
</style>

<script>
(function () {
    const root = document.getElementById('msc-chatbot');
    if (!root || root.dataset.ready) return;
    root.dataset.ready = '1';

    const STORE_KEY = 'msc-chat-v1';
    const OPEN_KEY = 'msc-chat-open';
    const fab = document.getElementById('msc-fab');
    const panel = document.getElementById('msc-panel');
    const list = document.getElementById('msc-messages');
    const form = document.getElementById('msc-form');
    const input = document.getElementById('msc-text');
    const sendBtn = document.getElementById('msc-send');
    const suggestionBox = document.getElementById('msc-suggestions');
    const samplesToggle = document.getElementById('msc-samples-toggle');
    const SAMPLES_KEY = 'msc-samples-collapsed';
    const samples = JSON.parse(root.dataset.suggestions || '[]');
    const mobile = window.matchMedia('(max-width: 640px), (max-height: 500px)');
    const touch = window.matchMedia('(hover: none), (pointer: coarse)');
    const canAutofocus = () => !mobile.matches && !touch.matches; // tránh bật bàn phím ảo
    let generation = 0; // tăng khi bấm "cuộc trò chuyện mới" để bỏ câu trả lời đang chờ

    const SPARK = '<svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2 5 5 2-5 2-2 5-2-5-5-2 5-2z"/></svg>';
    const SPARK_BIG = '<svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2 5 5 2-5 2-2 5-2-5-5-2 5-2z"/><path d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9z" opacity=".7"/></svg>';

    let messages = load();
    let busy = false;

    function load() {
        try { return JSON.parse(sessionStorage.getItem(STORE_KEY)) || []; } catch (e) { return []; }
    }
    function save() {
        try { sessionStorage.setItem(STORE_KEY, JSON.stringify(messages.slice(-40))); } catch (e) {}
    }

    function escapeHtml(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }
    // Định dạng tối giản: **đậm**, đoạn văn, danh sách "- " và "1. "
    function formatText(text) {
        const lines = escapeHtml(text).split('\n');
        let html = '', list = null;
        const close = () => { if (list) { html += `</${list}>`; list = null; } };
        for (const raw of lines) {
            const line = raw.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            const bullet = line.match(/^\s*[-•*]\s+(.*)$/);
            const numbered = line.match(/^\s*\d+[.)]\s+(.*)$/);
            if (bullet || numbered) {
                const type = bullet ? 'ul' : 'ol';
                if (list !== type) { close(); html += `<${type}>`; list = type; }
                html += `<li>${(bullet || numbered)[1]}</li>`;
            } else {
                close();
                if (line.trim() !== '') html += `<p>${line}</p>`;
            }
        }
        close();
        return html;
    }
    const money = v => Number(v).toLocaleString('vi-VN') + 'đ';
    const safeUrl = url => (/^(https?:)?\/\//i.test(url || '') || String(url || '').startsWith('/')) ? url : '#';
    const clock = t => t ? new Date(t).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }) : '';

    function cardHtml(p) {
        const swatches = (p.swatches || []).slice(0, 5)
            .map(s => `<span class="msc-swatch" style="background:${/^#[0-9a-f]{3,8}$/i.test(s.hex) ? s.hex : '#ccc'}" title="${escapeHtml(s.name)}"></span>`).join('');
        // Nhiều size thì hiển thị dạng khoảng: "Size S–XXL"
        const sizeList = p.sizes || [];
        const sizes = !sizeList.length ? '' : (sizeList.length > 3 ? `Size ${sizeList[0]}–${sizeList[sizeList.length - 1]}` : 'Size ' + sizeList.join(' · '))
            .replace('Size FREE', 'Free size');
        return `
            <a class="msc-card" href="${escapeHtml(safeUrl(p.url))}">
                <div class="msc-card-img">
                    <img src="${escapeHtml(safeUrl(p.image))}" alt="${escapeHtml(p.name)}" loading="lazy" data-fallback>
                    ${p.discount ? `<span class="msc-badge">-${p.discount}%</span>` : ''}
                </div>
                <div class="msc-card-body">
                    ${p.category ? `<div class="msc-card-cat">${escapeHtml(p.category)}</div>` : ''}
                    <div class="msc-card-name">${escapeHtml(p.name)}</div>
                    <div class="msc-price"><b>${money(p.price)}</b>${p.original_price ? `<s>${money(p.original_price)}</s>` : ''}</div>
                    ${swatches || sizes ? `<div class="msc-meta"><span class="msc-swatches">${swatches}</span><span class="msc-sizes" title="${escapeHtml(sizes)}">${escapeHtml(sizes)}</span></div>` : ''}
                    <span class="msc-card-btn">Xem chi tiết <span aria-hidden="true">→</span></span>
                </div>
            </a>`;
    }

    const THINK_STEPS = ['Đang đọc hiểu câu hỏi...', 'Đang phân tích nhu cầu...', 'Đang tìm trong kho hàng...', 'Đang chọn sản phẩm phù hợp...'];
    let thinkTimer = null;
    function thinkingHtml() {
        const skel = '<div class="msc-skel"><div class="msc-skel-img"></div><div class="msc-skel-line"></div><div class="msc-skel-line short"></div></div>';
        return `<div class="msc-row is-bot is-new is-thinking"><span class="msc-mini-avatar" aria-hidden="true">${SPARK}</span>
            <div class="msc-col"><div class="msc-sender">MODESPORT AI</div>
                <div class="msc-thinking" id="msc-typing" role="status">
                    <div class="msc-think-line"><span class="msc-orb" aria-hidden="true"></span><span class="msc-think-text">${THINK_STEPS[0]}</span></div>
                    <div class="msc-skeletons" aria-hidden="true">${skel}${skel}${skel}</div>
                </div>
            </div></div>`;
    }
    function startThinking() {
        list.insertAdjacentHTML('beforeend', thinkingHtml());
        let step = 0;
        clearInterval(thinkTimer);
        thinkTimer = setInterval(() => {
            const el = document.querySelector('#msc-typing .msc-think-text');
            if (!el) return clearInterval(thinkTimer);
            step = Math.min(step + 1, THINK_STEPS.length - 1);
            el.textContent = THINK_STEPS[step];
            el.classList.remove('is-swap'); void el.offsetWidth; el.classList.add('is-swap');
        }, 1300);
    }
    function stopThinking() { clearInterval(thinkTimer); thinkTimer = null; }

    function botRow(inner, isNew) {
        return `<div class="msc-row is-bot${isNew ? ' is-new' : ''}"><span class="msc-mini-avatar" aria-hidden="true">${SPARK}</span><div class="msc-col"><div class="msc-sender">MODESPORT AI</div>${inner}</div></div>`;
    }

    function heroHtml() {
        return `<div class="msc-hero">
            <div class="msc-hero-avatar">${SPARK_BIG}</div>
            <h3>Xin chào! 👋</h3>
            <p>Mình là trợ lý AI của MODESPORT. Hãy cho mình biết bạn cần tìm gì, mình sẽ gợi ý sản phẩm phù hợp nhất.</p>
            <div class="msc-skills"><span>🔎 Tìm sản phẩm</span><span>📏 Tư vấn size</span><span>👕 Phối cả bộ</span><span>💰 Lọc theo giá</span><span>🚚 Ship & đổi trả</span></div>
        </div>`;
    }

    function messageHtml(m, isNew) {
        const time = m.time ? `<div class="msc-time">${clock(m.time)}</div>` : '';
        if (m.role === 'user') {
            return `<div class="msc-row is-user${isNew ? ' is-new' : ''}"><div class="msc-col"><div class="msc-bubble">${escapeHtml(m.text)}</div>${time}</div></div>`;
        }
        let inner = `<div class="msc-bubble">${formatText(m.reply)}</div>`;
        if (m.analysis && m.analysis.length) {
            inner += `<div class="msc-analysis"><span class="msc-analysis-label">✦ AI hiểu bạn cần</span>${m.analysis.map(a => `<span class="msc-tag">${escapeHtml(a)}</span>`).join('')}</div>`;
        }
        if (m.products && m.products.length) {
            inner += `<div class="msc-cards">${m.products.map(cardHtml).join('')}</div>`;
        }
        for (const o of m.outfits || []) {
            inner += `<div class="msc-outfit">
                <div class="msc-outfit-head"><strong>${escapeHtml(o.title)}</strong><span class="msc-total">Tổng ${money(o.total)}</span></div>
                <div class="msc-outfit-items">${o.items.map(cardHtml).join('<span class="msc-plus" aria-hidden="true">+</span>')}</div>
            </div>`;
        }
        if (m.links && m.links.length) {
            inner += `<div class="msc-links">${m.links.map(l => `<a class="msc-link" href="${escapeHtml(safeUrl(l.url))}">${escapeHtml(l.label)} →</a>`).join('')}</div>`;
        }
        const meta = [clock(m.time), m.source ? (m.source === 'gemini' ? 'Phân tích bởi Gemini AI' : 'Phân tích bởi bộ NLP tiếng Việt') : ''].filter(Boolean).join(' · ');
        if (meta) {
            inner += `<div class="msc-source">${meta}</div>`;
        }
        return botRow(inner, isNew);
    }

    function render(animateLast) {
        let html = heroHtml();
        if (messages.length) {
            html += '<div class="msc-divider">Hôm nay</div>';
            html += messages.map((m, i) => messageHtml(m, animateLast && i === messages.length - 1)).join('');
        }
        list.innerHTML = html;
        list.querySelectorAll('img[data-fallback]').forEach(img => {
            img.addEventListener('error', () => { img.removeAttribute('data-fallback'); img.src = root.dataset.placeholder; }, { once: true });
        });
        scrollToLatest();
    }

    // Đưa câu hỏi mới nhất lên đầu khung để khách đọc câu trả lời từ đầu
    function scrollToLatest() {
        if (!messages.length) { list.scrollTop = 0; return; }
        const last = messages[messages.length - 1];
        const userRows = list.querySelectorAll('.msc-row.is-user');
        const lastUser = userRows[userRows.length - 1];
        list.scrollTop = last.role === 'bot' && lastUser ? Math.max(0, lastUser.offsetTop - 8) : list.scrollHeight;
    }

    // Câu hỏi gợi ý luôn nằm sát ô nhập; gợi ý hỏi tiếp sau mỗi câu trả lời xếp phía trước
    function setSuggestions(followUps) {
        const extra = (followUps || []).filter(s => !samples.includes(s));
        const chip = (s, cls) => `<button type="button" class="${cls}" data-ask="${escapeHtml(s)}">${escapeHtml(s)}</button>`;
        suggestionBox.innerHTML = extra.map(s => chip(s, 'is-followup')).join('') + samples.map(s => chip(s, 'is-sample')).join('');
        suggestionBox.scrollTop = 0;
    }

    function lockPage(lock) {
        document.documentElement.classList.toggle('msc-lock', lock && mobile.matches);
    }
    function open() {
        panel.hidden = false;
        fab.classList.add('is-open');
        fab.setAttribute('aria-expanded', 'true');
        lockPage(true);
        panel.setAttribute('aria-modal', mobile.matches ? 'true' : 'false');
        try { sessionStorage.setItem(OPEN_KEY, '1'); } catch (e) {}
        render();
        setTimeout(() => (canAutofocus() ? input : panel).focus({ preventScroll: true }), 60);
    }
    function close() {
        panel.hidden = true;
        fab.classList.remove('is-open');
        fab.setAttribute('aria-expanded', 'false');
        lockPage(false);
        try { sessionStorage.removeItem(OPEN_KEY); } catch (e) {}
        fab.focus({ preventScroll: true });
    }

    async function send(text) {
        text = String(text || '').trim();
        if (!text || busy) return;
        const myGeneration = generation;
        busy = true;
        setSamplesCollapsed(true); // nhường chỗ cho câu trả lời (bấm "Hiện" để mở lại gợi ý)
        sendBtn.disabled = true;
        input.value = '';
        messages.push({ role: 'user', text, time: Date.now() });
        save();
        render(true);
        startThinking();
        list.scrollTop = list.scrollHeight;

        try {
            const res = await fetch(root.dataset.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': root.dataset.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: JSON.stringify({ message: text }),
            });
            if (res.status === 429) throw new Error('Bạn gửi tin nhắn hơi nhanh, vui lòng đợi một chút rồi thử lại nhé.');
            if (res.status === 419) throw new Error('Phiên làm việc đã hết hạn, vui lòng tải lại trang.');
            if (!res.ok) throw new Error('Xin lỗi, hệ thống đang bận. Bạn vui lòng thử lại sau.');
            const data = await res.json();
            if (myGeneration !== generation) return; // đã bắt đầu cuộc trò chuyện mới
            messages.push({ role: 'bot', ...data, time: Date.now() });
            setSuggestions(data.suggestions);
        } catch (err) {
            if (myGeneration !== generation) return;
            messages.push({ role: 'bot', reply: err.message || 'Không thể kết nối tới máy chủ.', time: Date.now() });
            if (!input.value) input.value = text; // giữ lại câu hỏi để gửi lại
            setSuggestions([text]);
        } finally {
            if (myGeneration === generation) {
                stopThinking();
                busy = false;
                sendBtn.disabled = false;
                save();
                render(true);
                if (canAutofocus()) input.focus();
            }
        }
    }

    fab.addEventListener('click', () => (panel.hidden ? open() : close()));
    document.getElementById('msc-close').addEventListener('click', close);
    document.getElementById('msc-reset').addEventListener('click', async () => {
        generation++;
        stopThinking();
        setSamplesCollapsed(false);
        busy = false;
        sendBtn.disabled = false;
        messages = [];
        save();
        render();
        setSuggestions([]);
        try {
            await fetch(root.dataset.reset, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': root.dataset.csrf }, credentials: 'same-origin' });
        } catch (e) {}
    });
    form.addEventListener('submit', e => { e.preventDefault(); send(input.value); });
    panel.addEventListener('click', e => {
        const btn = e.target.closest('[data-ask]');
        if (btn) send(btn.dataset.ask);
    });
    // Bảng "Demo": 5 tình huống bắt buộc khi bảo vệ đề tài
    const demoBtn = document.getElementById('msc-demo-btn');
    const demoBox = document.getElementById('msc-demo');
    function toggleDemo(show) {
        if (!demoBtn || !demoBox) return;
        demoBox.hidden = !show;
        demoBtn.setAttribute('aria-expanded', String(show));
    }
    demoBtn?.addEventListener('click', () => toggleDemo(demoBox.hidden));
    demoBox?.addEventListener('click', e => { if (e.target.closest('[data-ask]')) toggleDemo(false); });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && demoBox && !demoBox.hidden) { toggleDemo(false); return; }
        if (e.key === 'Escape' && !panel.hidden) close();
        // Alt+1 … Alt+5: gửi nhanh tình huống demo tương ứng
        if (demoBox && e.altKey && /^Digit[1-5]$/.test(e.code)) {
            const btn = demoBox.querySelector(`[data-demo="${e.code.slice(5)}"]`);
            if (!btn) return;
            e.preventDefault();
            if (panel.hidden) open();
            toggleDemo(false);
            send(btn.dataset.ask);
        }
    });
    mobile.addEventListener?.('change', () => lockPage(!panel.hidden));
    // Thu gọn / mở rộng danh sách câu hỏi gợi ý (ghi nhớ trong phiên)
    function setSamplesCollapsed(collapsed) {
        suggestionBox.classList.toggle('is-collapsed', collapsed);
        samplesToggle.setAttribute('aria-expanded', String(!collapsed));
        samplesToggle.textContent = collapsed ? 'Hiện' : 'Thu gọn';
        try { collapsed ? sessionStorage.setItem(SAMPLES_KEY, '1') : sessionStorage.removeItem(SAMPLES_KEY); } catch (e) {}
    }
    samplesToggle.addEventListener('click', () => setSamplesCollapsed(!suggestionBox.classList.contains('is-collapsed')));
    try { setSamplesCollapsed(!!sessionStorage.getItem(SAMPLES_KEY)); } catch (e) {}

    const last = [...messages].reverse().find(m => m.role === 'bot' && m.suggestions);
    setSuggestions(last ? last.suggestions : []);
    try { if (sessionStorage.getItem(OPEN_KEY)) open(); } catch (e) {}
})();
</script>
