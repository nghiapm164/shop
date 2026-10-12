{{-- AI SPORT ASSISTANT – chatbot tư vấn sản phẩm (nút nổi góc dưới màn hình) --}}
<div id="msc-chatbot"
     data-endpoint="{{ route('chatbot.message') }}"
     data-reset="{{ route('chatbot.reset') }}"
     data-csrf="{{ csrf_token() }}"
     data-placeholder="{{ asset('images/product-placeholder.svg') }}"
     data-suggestions='@json(\App\Services\Chatbot\ChatbotService::suggestions())'>

    <button type="button" class="msc-fab" id="msc-fab" aria-label="Mở AI Sport Assistant" aria-controls="msc-panel" aria-expanded="false">
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
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2.5l2 5 5 2-5 2-2 5-2-5-5-2 5-2z"/><path d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9z" opacity=".75"/></svg>
            </div>
            <div class="msc-title">
                <strong id="msc-title">AI Sport Assistant</strong>
                <span class="msc-status"><i class="msc-dot"></i><span class="msc-status-text">Trực tuyến · trả lời trong vài giây</span></span>
            </div>
            @if (config('chatbot.demo_mode'))
                <button type="button" class="msc-demo-btn" id="msc-demo-btn" aria-expanded="false" aria-controls="msc-demo" title="5 tình huống demo (Alt+1 … Alt+5)">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                    Demo
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
                <input type="text" id="msc-text" maxlength="500" enterkeyhint="send"
                       placeholder="Hỏi về sản phẩm, size, giá..." aria-label="Nhập câu hỏi">
                <button type="submit" id="msc-send" aria-label="Gửi">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </button>
            </form>
            <p class="msc-note">AI chỉ tư vấn dựa trên dữ liệu sản phẩm của shop.</p>
        </div>
    </section>
</div>

<style>
    #msc-chatbot {
        --msc-primary: #E42508; --msc-primary-dark: #b81d05; --msc-primary-soft: #fff1ee;
        --msc-ink: #161922; --msc-ink-2: #2a2f3b; --msc-text: #1e2330; --msc-muted: #6b7280;
        --msc-border: #e7e9ee; --msc-bg: #f5f6f8; --msc-card: #ffffff;
        --msc-radius: 18px; --msc-shadow: 0 24px 64px -16px rgba(16, 20, 30, .35);
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
        background: linear-gradient(135deg, #f0360f 0%, var(--msc-primary) 55%, var(--msc-primary-dark) 100%);
        color: #fff; font-weight: 700; font-size: 15px; letter-spacing: .01em; cursor: pointer;
        box-shadow: 0 12px 28px -8px rgba(228, 37, 8, .6), inset 0 1px 0 rgba(255, 255, 255, .25);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .msc-fab:hover { transform: translateY(-2px); box-shadow: 0 16px 32px -8px rgba(228, 37, 8, .7); }
    .msc-fab:focus-visible { outline: 3px solid rgba(228, 37, 8, .35); outline-offset: 3px; }
    .msc-fab-icon { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; background: rgba(255, 255, 255, .16); }
    .msc-fab-ring { position: absolute; inset: 0; border-radius: inherit; pointer-events: none; animation: msc-ring 2.6s ease-out infinite; }
    .msc-fab.is-open .msc-fab-ring { animation: none; }
    @keyframes msc-ring { 0% { box-shadow: 0 0 0 0 rgba(228, 37, 8, .45); } 70%, 100% { box-shadow: 0 0 0 14px rgba(228, 37, 8, 0); } }

    /* ---------- Khung chat ---------- */
    /* Desktop: sidebar cao hết màn hình bên phải */
    .msc-panel {
        outline: none;
        position: fixed; top: 0; right: 0; bottom: 0; z-index: 9999;
        width: clamp(400px, 32vw, 480px); height: 100vh; height: 100dvh;
        display: flex; flex-direction: column; overflow: hidden;
        background: var(--msc-bg); color: var(--msc-text);
        border-left: 1px solid rgba(16, 20, 30, .08); box-shadow: -18px 0 48px -16px rgba(16, 20, 30, .35);
        animation: msc-in .25s cubic-bezier(.2, .8, .2, 1);
    }
    @keyframes msc-in { from { opacity: 0; transform: translateX(32px); } to { opacity: 1; transform: none; } }
    .msc-fab.is-open { display: none; }

    .msc-header {
        flex: none; display: flex; align-items: center; gap: 10px;
        padding: 14px 10px 14px 16px; color: #fff;
        background: radial-gradient(120% 140% at 0% 0%, #2c3140 0%, var(--msc-ink) 60%);
    }
    .msc-avatar {
        flex: none; width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; color: #fff;
        background: linear-gradient(135deg, #ff5a36, var(--msc-primary)); box-shadow: 0 6px 14px -6px rgba(228, 37, 8, .8);
    }
    .msc-title { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
    .msc-title strong { font-size: 15.5px; font-weight: 800; letter-spacing: .02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-status { font-size: 12px; color: #b8bfcc; display: flex; align-items: center; gap: 6px; min-width: 0; }
    .msc-status-text { min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-dot { flex: none; width: 7px; height: 7px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, .2); }
    .msc-icon-btn {
        flex: none; width: 44px; height: 44px; display: grid; place-items: center;
        border: 0; border-radius: 10px; background: transparent; color: #d5d9e2; cursor: pointer; transition: background .15s;
    }
    .msc-icon-btn:hover, .msc-icon-btn:focus-visible { background: rgba(255, 255, 255, .12); color: #fff; outline: none; }

    /* ---------- Nút & bảng Demo (5 tình huống bắt buộc) ---------- */
    .msc-demo-btn {
        flex: none; display: inline-flex; align-items: center; gap: 5px; height: 32px; padding: 0 11px; border-radius: 999px; cursor: pointer;
        border: 1px solid rgba(255, 255, 255, .25); background: rgba(255, 255, 255, .08); color: #fff; font-size: 12.5px; font-weight: 800; letter-spacing: .02em;
        transition: background .15s, border-color .15s;
    }
    .msc-demo-btn:hover, .msc-demo-btn[aria-expanded="true"] { background: var(--msc-primary); border-color: var(--msc-primary); }
    .msc-demo {
        flex: none; max-height: 62%; overflow-y: auto; overscroll-behavior: contain;
        background: #fff; border-bottom: 1px solid var(--msc-border); box-shadow: 0 10px 24px -18px rgba(16, 20, 30, .6);
        animation: msc-drop .18s ease-out;
    }
    @keyframes msc-drop { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }
    .msc-demo-head { display: flex; flex-direction: column; gap: 2px; padding: 12px 14px 6px; }
    .msc-demo-head strong { font-size: 13.5px; font-weight: 800; }
    .msc-demo-head span { font-size: 11.5px; color: var(--msc-muted); }
    #msc-chatbot .msc-demo-list { list-style: none; margin: 0; padding: 4px 10px 12px; display: flex; flex-direction: column; gap: 6px; }
    .msc-demo-list button {
        width: 100%; display: flex; gap: 10px; align-items: flex-start; text-align: left; cursor: pointer;
        padding: 10px 12px; border-radius: 12px; border: 1px solid var(--msc-border); background: var(--msc-bg); color: var(--msc-text);
        transition: border-color .15s, background .15s;
    }
    .msc-demo-list button:hover, .msc-demo-list button:focus-visible { border-color: #f6b4a6; background: var(--msc-primary-soft); outline: none; }
    .msc-demo-no {
        flex: none; width: 26px; height: 26px; border-radius: 8px; display: grid; place-items: center;
        background: linear-gradient(135deg, #ff5a36, var(--msc-primary)); color: #fff; font-weight: 800; font-size: 13px;
    }
    .msc-demo-body { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .msc-demo-title { font-size: 11.5px; font-weight: 800; color: var(--msc-primary); text-transform: uppercase; letter-spacing: .03em; }
    .msc-demo-q { font-size: 13.5px; font-weight: 600; line-height: 1.4; }
    .msc-demo-expect { font-size: 11.5px; color: var(--msc-muted); }

    /* ---------- Tin nhắn ---------- */
    .msc-messages {
        position: relative;
        flex: 1; min-height: 0; overflow-y: auto; overscroll-behavior: contain; -webkit-overflow-scrolling: touch;
        padding: 18px 14px 12px; display: flex; flex-direction: column; gap: 14px; scroll-behavior: smooth;
    }
    .msc-row { display: flex; gap: 8px; align-items: flex-end; min-width: 0; max-width: 100%; }
    .msc-row.is-user { justify-content: flex-end; }
    .msc-row.is-bot { align-items: flex-start; }
    .msc-mini-avatar {
        flex: none; width: 28px; height: 28px; border-radius: 9px; display: grid; place-items: center; margin-top: 2px;
        color: #fff; background: linear-gradient(135deg, #ff5a36, var(--msc-primary));
    }
    .msc-col { display: flex; flex-direction: column; gap: 8px; min-width: 0; flex: 1; }
    .msc-bubble { max-width: 100%; padding: 11px 14px; border-radius: 16px; font-size: 14.5px; line-height: 1.55; overflow-wrap: anywhere; }
    .msc-row.is-user .msc-bubble {
        max-width: 82%; background: linear-gradient(135deg, #f0360f, var(--msc-primary)); color: #fff;
        border-bottom-right-radius: 5px; box-shadow: 0 6px 16px -10px rgba(228, 37, 8, .9);
    }
    .msc-row.is-bot .msc-bubble { align-self: flex-start; background: var(--msc-card); border: 1px solid var(--msc-border); border-top-left-radius: 5px; box-shadow: 0 2px 6px -4px rgba(16, 20, 30, .15); }
    .msc-bubble strong { font-weight: 700; color: inherit; }
    .msc-bubble p { margin: 0; }
    .msc-bubble p + p, .msc-bubble p + ul, .msc-bubble p + ol, .msc-bubble ul + p, .msc-bubble ol + p { margin-top: 6px; }
    #msc-chatbot .msc-bubble ul, #msc-chatbot .msc-bubble ol { margin: 4px 0 0; padding-left: 20px; }
    #msc-chatbot .msc-bubble ul { list-style: disc outside; }
    #msc-chatbot .msc-bubble ol { list-style: decimal outside; }
    #msc-chatbot .msc-bubble li { display: list-item; margin: 3px 0; }
    .msc-bubble li::marker { color: var(--msc-primary); }


    /* AI đã phân tích */
    .msc-analysis { display: flex; flex-wrap: wrap; align-items: center; gap: 5px; }
    .msc-analysis-label { font-size: 11px; font-weight: 700; color: var(--msc-muted); text-transform: uppercase; letter-spacing: .05em; margin-right: 2px; }
    .msc-tag { font-size: 11.5px; padding: 3px 9px; border-radius: 999px; background: var(--msc-primary-soft); color: #b42010; border: 1px solid #fcd5cc; white-space: nowrap; }

    /* Thẻ sản phẩm */
    .msc-cards { display: flex; gap: 10px; overflow-x: auto; overscroll-behavior-x: contain; padding: 2px 2px 6px; scroll-snap-type: x mandatory; scrollbar-width: thin; }
    .msc-cards::-webkit-scrollbar, .msc-messages::-webkit-scrollbar { height: 6px; width: 6px; }
    .msc-cards::-webkit-scrollbar-thumb, .msc-messages::-webkit-scrollbar-thumb { background: #cfd4dc; border-radius: 3px; }
    .msc-card {
        flex: none; width: 140px; display: flex; flex-direction: column; overflow: hidden; scroll-snap-align: start;
        background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 14px;
        text-decoration: none !important; color: inherit !important; transition: transform .18s, box-shadow .18s;
    }
    .msc-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -14px rgba(16, 20, 30, .45); }
    .msc-card-img { position: relative; aspect-ratio: 4 / 5; background: #eceef2; overflow: hidden; }
    .msc-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .msc-badge { position: absolute; top: 8px; left: 8px; background: var(--msc-primary); color: #fff; font-size: 11px; font-weight: 800; padding: 3px 7px; border-radius: 6px; }
    .msc-card-body { flex: 1; display: flex; flex-direction: column; gap: 6px; padding: 9px 10px 10px; }
    .msc-card-name { font-size: 13px; font-weight: 600; line-height: 1.35; min-height: 35px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .msc-price { display: flex; align-items: baseline; flex-wrap: wrap; gap: 0 6px; }
    .msc-price b { color: var(--msc-primary); font-size: 15px; font-weight: 800; }
    .msc-price s { color: #9aa1ad; font-size: 11.5px; }
    .msc-meta { display: flex; align-items: center; justify-content: space-between; gap: 6px; font-size: 11px; color: var(--msc-muted); }
    .msc-swatches { display: flex; gap: 3px; }
    .msc-swatch { width: 12px; height: 12px; border-radius: 50%; border: 1px solid rgba(0, 0, 0, .15); }
    .msc-sizes { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msc-card-btn { margin-top: auto; text-align: center; font-size: 12.5px; font-weight: 700; padding: 8px 0; border-radius: 9px; background: var(--msc-ink); color: #fff; transition: background .15s; }
    .msc-card:hover .msc-card-btn { background: var(--msc-primary); }

    /* Bộ đồ */
    .msc-outfit { background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 16px; padding: 10px; min-width: 0; }
    .msc-outfit-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin: 0 2px 8px; font-size: 13px; }
    .msc-outfit-head strong { font-weight: 800; }
    .msc-total { font-size: 12px; font-weight: 800; color: var(--msc-primary); background: var(--msc-primary-soft); padding: 4px 9px; border-radius: 999px; white-space: nowrap; }
    .msc-outfit-items { display: grid; grid-template-columns: 1fr auto 1fr; align-items: stretch; gap: 6px; }
    .msc-outfit-items .msc-card { width: auto; min-width: 0; }
    .msc-plus { align-self: center; width: 22px; height: 22px; border-radius: 50%; display: grid; place-items: center; background: var(--msc-bg); color: var(--msc-muted); font-weight: 800; font-size: 14px; }

    .msc-links { display: flex; flex-wrap: wrap; gap: 6px; }
    .msc-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 700; padding: 7px 12px; border-radius: 999px; border: 1px solid #f6b4a6; color: var(--msc-primary) !important; background: #fff; text-decoration: none !important; }
    .msc-link:hover { background: var(--msc-primary); border-color: var(--msc-primary); color: #fff !important; }
    .msc-source { font-size: 10.5px; color: #9aa1ad; display: flex; align-items: center; gap: 4px; }

    .msc-typing { display: inline-flex; align-items: center; gap: 8px; padding: 11px 14px; background: var(--msc-card); border: 1px solid var(--msc-border); border-radius: 16px; border-top-left-radius: 5px; font-size: 12.5px; color: var(--msc-muted); }
    .msc-dots { display: inline-flex; gap: 4px; }
    .msc-dots i { width: 6px; height: 6px; border-radius: 50%; background: #aab1bd; animation: msc-blink 1.2s infinite; }
    .msc-dots i:nth-child(2) { animation-delay: .15s; } .msc-dots i:nth-child(3) { animation-delay: .3s; }
    @keyframes msc-blink { 0%, 80%, 100% { opacity: .35; transform: translateY(0); } 40% { opacity: 1; transform: translateY(-3px); } }

    /* ---------- Chân khung chat ---------- */
    .msc-footer { flex: none; background: #fff; border-top: 1px solid var(--msc-border); padding-bottom: env(safe-area-inset-bottom, 0); }
    /* Câu hỏi mẫu: danh sách dọc, cuộn được, thu gọn được */
    .msc-samples-head { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px 0 14px; }
    .msc-samples-head span { font-size: 11px; font-weight: 800; color: var(--msc-muted); text-transform: uppercase; letter-spacing: .06em; }
    .msc-samples-head button { border: 0; background: none; padding: 6px 4px; font-size: 12px; font-weight: 700; color: var(--msc-primary); cursor: pointer; }
    .msc-samples-head button::after { content: ' ▾'; }
    .msc-samples-head button[aria-expanded="false"]::after { content: ' ▴'; }
    .msc-suggestions {
        display: flex; flex-direction: column; gap: 6px;
        max-height: 38vh; max-height: 38dvh; overflow-y: auto; overscroll-behavior: contain;
        padding: 6px 12px 6px; scrollbar-width: thin; scrollbar-color: #d5d9e0 transparent;
    }
    .msc-suggestions::-webkit-scrollbar { width: 5px; }
    .msc-suggestions::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 3px; }
    .msc-suggestions button.is-followup { background: #fff; border-style: dashed; }
    .msc-suggestions:empty, .msc-suggestions.is-collapsed { display: none; }
    .msc-suggestions button {
        flex: none; display: flex; align-items: flex-start; gap: 8px; width: 100%; min-height: 38px;
        text-align: left; font-size: 13px; line-height: 1.4; padding: 8px 12px; border-radius: 12px; white-space: normal; cursor: pointer;
        border: 1px solid var(--msc-border); background: var(--msc-bg); color: var(--msc-ink-2); transition: all .15s;
    }
    .msc-suggestions button:hover { border-color: #f6b4a6; color: var(--msc-primary); background: var(--msc-primary-soft); }
    .msc-input { display: flex; align-items: center; gap: 8px; padding: 10px 12px 4px; margin: 0; }
    .msc-input input {
        flex: 1; min-width: 0; height: 46px; padding: 0 16px; margin: 0;
        font-family: inherit; font-size: 15px; color: var(--msc-text);
        background: var(--msc-bg); border: 1px solid transparent; border-radius: 999px; outline: none; box-shadow: none;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .msc-input input::placeholder { color: #9aa1ad; }
    .msc-input input:focus { background: #fff; border-color: #f3a593; box-shadow: 0 0 0 4px rgba(228, 37, 8, .1); }
    .msc-input button {
        flex: none; width: 46px; height: 46px; border: 0; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
        color: #fff; background: linear-gradient(135deg, #f0360f, var(--msc-primary)); box-shadow: 0 8px 16px -8px rgba(228, 37, 8, .9);
        transition: transform .15s, opacity .15s;
    }
    .msc-input button:hover { transform: scale(1.05); }
    .msc-input button:disabled { opacity: .45; cursor: not-allowed; transform: none; }
    .msc-note { margin: 0; padding: 2px 12px 9px; font-size: 10.5px; color: #9aa1ad; text-align: center; }

    /* ---------- Mobile: toàn màn hình ---------- */
    @media (max-width: 640px), (max-height: 500px) {
        .msc-fab { right: 14px; bottom: calc(14px + env(safe-area-inset-bottom, 0px)); height: 54px; width: 54px; padding: 0; justify-content: center; }
        .msc-fab-icon { background: transparent; }
        .msc-fab-label { display: none; }
        .msc-panel {
            inset: 0; right: auto; bottom: auto; width: 100%; height: 100%; height: 100dvh; max-height: none;
            border: 0; border-radius: 0; box-shadow: none; animation-name: msc-in-mobile;
        }
        @keyframes msc-in-mobile { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        .msc-header { padding-top: calc(12px + env(safe-area-inset-top, 0px)); }
        .msc-messages { padding: 14px 12px 10px; }
        .msc-card { width: 140px; }
        .msc-input input { font-size: 16px; } /* >= 16px để iPhone không tự phóng to khi gõ */
    }
    @media (max-width: 640px) {
        body { padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px)); } /* chừa chỗ cho nút chat nổi */
    }
    /* Màn hình rất thấp (xoay ngang / bàn phím ảo đang mở): thu gọn phần đầu & chân */
    @media (max-height: 500px) {
        .msc-note, .msc-suggestions, .msc-samples-head { display: none; }
        .msc-header { padding-top: 6px; padding-bottom: 6px; }
        .msc-avatar { width: 32px; height: 32px; }
        .msc-status { display: none; }
        .msc-input { padding: 6px 12px; }
        .msc-input input, .msc-input button { height: 40px; }
        .msc-input button { width: 40px; }
    }
    @media (max-width: 360px) {
        .msc-card { width: 132px; }
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
        .msc-fab-ring, .msc-panel, .msc-dots i { animation: none !important; }
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

    function cardHtml(p) {
        const swatches = (p.swatches || []).slice(0, 5)
            .map(s => `<span class="msc-swatch" style="background:${/^#[0-9a-f]{3,8}$/i.test(s.hex) ? s.hex : '#ccc'}" title="${escapeHtml(s.name)}"></span>`).join('');
        // Nhiều size thì hiển thị dạng khoảng: "Size S–XXL"
        const list = p.sizes || [];
        const sizes = !list.length ? '' : (list.length > 3 ? `Size ${list[0]}–${list[list.length - 1]}` : 'Size ' + list.join(' · '))
            .replace('Size FREE', 'Free size');
        return `
            <a class="msc-card" href="${escapeHtml(safeUrl(p.url))}">
                <div class="msc-card-img">
                    <img src="${escapeHtml(safeUrl(p.image))}" alt="${escapeHtml(p.name)}" loading="lazy" data-fallback>
                    ${p.discount ? `<span class="msc-badge">-${p.discount}%</span>` : ''}
                </div>
                <div class="msc-card-body">
                    <div class="msc-card-name">${escapeHtml(p.name)}</div>
                    <div class="msc-price"><b>${money(p.price)}</b>${p.original_price ? `<s>${money(p.original_price)}</s>` : ''}</div>
                    ${swatches || sizes ? `<div class="msc-meta"><span class="msc-swatches">${swatches}</span><span class="msc-sizes" title="${escapeHtml(sizes)}">${escapeHtml(sizes)}</span></div>` : ''}
                    <span class="msc-card-btn">Xem sản phẩm</span>
                </div>
            </a>`;
    }

    function botRow(inner) {
        return `<div class="msc-row is-bot"><span class="msc-mini-avatar" aria-hidden="true">${SPARK}</span><div class="msc-col">${inner}</div></div>`;
    }

    function welcomeHtml() {
        return botRow(`
            <div class="msc-bubble"><p>Xin chào! 👋 Tôi có thể giúp bạn tìm quần áo thể thao phù hợp.</p><p>Bạn đang muốn tìm sản phẩm gì? Bạn có thể chọn nhanh một <strong>câu hỏi gợi ý</strong> bên dưới.</p></div>
`);
    }

    function messageHtml(m) {
        if (m.role === 'user') {
            return `<div class="msc-row is-user"><div class="msc-bubble">${escapeHtml(m.text)}</div></div>`;
        }
        let inner = `<div class="msc-bubble">${formatText(m.reply)}</div>`;
        if (m.analysis && m.analysis.length) {
            inner += `<div class="msc-analysis"><span class="msc-analysis-label">AI hiểu</span>${m.analysis.map(a => `<span class="msc-tag">${escapeHtml(a)}</span>`).join('')}</div>`;
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
        if (m.source) {
            inner += `<div class="msc-source">${m.source === 'gemini' ? '✦ Phân tích bởi Gemini AI' : '✦ Phân tích bởi bộ NLP tiếng Việt'} · dữ liệu từ website</div>`;
        }
        return botRow(inner);
    }

    function render() {
        list.innerHTML = welcomeHtml() + messages.map(messageHtml).join('');
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

    // Hàng câu hỏi mẫu (theo đề bài) luôn nằm sát ô nhập; gợi ý nối tiếp sau mỗi câu trả lời xếp phía trước
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
        messages.push({ role: 'user', text });
        save();
        render();
        list.insertAdjacentHTML('beforeend', botRow('<div class="msc-typing" id="msc-typing"><span class="msc-dots"><i></i><i></i><i></i></span>AI đang phân tích...</div>'));
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
            messages.push({ role: 'bot', ...data });
            setSuggestions(data.suggestions);
        } catch (err) {
            if (myGeneration !== generation) return;
            messages.push({ role: 'bot', reply: err.message || 'Không thể kết nối tới máy chủ.' });
            if (!input.value) input.value = text; // giữ lại câu hỏi để gửi lại
            setSuggestions([text]);
        } finally {
            if (myGeneration === generation) {
                busy = false;
                sendBtn.disabled = false;
                save();
                render();
                if (canAutofocus()) input.focus();
            }
        }
    }

    fab.addEventListener('click', () => (panel.hidden ? open() : close()));
    document.getElementById('msc-close').addEventListener('click', close);
    document.getElementById('msc-reset').addEventListener('click', async () => {
        generation++;
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
    // Thu gọn / mở rộng danh sách câu hỏi mẫu (ghi nhớ trong phiên)
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
