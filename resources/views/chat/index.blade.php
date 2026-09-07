{{-- ============================================================
     AI Legal Assistant Chatbot Widget
     Requires: Font Awesome 6, Inter font, Laravel routes
     Guarded by: ai_feature_enabled setting
     ============================================================ --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@php $aiEnabled = isset($aiEnabled) ? (bool)$aiEnabled : (bool)(int) \App\Models\Setting::get('ai_feature_enabled', '0'); @endphp

{{-- ═══ LOCKED STATE (AI feature disabled) ═══ --}}
@if(!$aiEnabled)
<button id="lawyer-chat-toggle-btn-locked" style="
    position:fixed;bottom:24px;left:24px;
    background:linear-gradient(145deg,#374151,#1f2937);
    color:#6b7280;width:64px;height:64px;border-radius:50%;
    box-shadow:0 8px 28px rgba(0,0,0,.38);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;border:2px solid rgba(107,114,128,.35);
    z-index:999999;outline:none;transition:.25s;
    font-size:22px;
" title="المساعد القانوني الذكي — غير مفعَّل"
   onclick="document.getElementById('ai-locked-modal').style.display='flex'">
    🔒
</button>
<div id="ai-locked-modal" style="
    display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);
    z-index:9999999;align-items:center;justify-content:center;
    backdrop-filter:blur(8px);
">
    <div style="background:#1e293b;border:1px solid rgba(212,175,55,.2);border-radius:20px;padding:2.5rem;max-width:420px;width:90%;text-align:center;box-shadow:0 32px 80px rgba(0,0,0,.5);">
        <div style="font-size:3rem;margin-bottom:1rem;">🔒</div>
        <h2 style="color:#fff;font-size:1.3rem;font-weight:800;margin-bottom:.5rem;">المساعد القانوني الذكي</h2>
        <p style="color:#94a3b8;font-size:.9rem;line-height:1.6;margin-bottom:1.5rem;">
            ميزة الذكاء الاصطناعي القانوني غير مفعَّلة حالياً في هذا المكتب.<br>
            تواصل مع مسؤول النظام لتفعيل هذه الميزة الاحترافية.
        </p>
        @if(auth()->user()?->isAdmin())
            <a href="{{ route('settings.edit') }}" wire:navigate style="display:inline-block;background:linear-gradient(135deg,#d4af37,#b8960c);color:#0f172a;padding:.75rem 1.75rem;border-radius:10px;font-weight:800;text-decoration:none;font-size:.95rem;margin-bottom:.75rem;">⚙️ تفعيل من الإعدادات</a><br>
        @endif
        <button onclick="document.getElementById('ai-locked-modal').style.display='none'" style="background:none;border:1px solid rgba(255,255,255,.15);color:#94a3b8;padding:.5rem 1.25rem;border-radius:8px;cursor:pointer;font-size:.85rem;margin-top:.5rem;">إغلاق</button>
    </div>
</div>
@else
{{-- ═══ FULL CHAT WIDGET (ai_feature_enabled = true) ═══ --}}


<style>
/* ================================================================
   RESET & BASE
   ================================================================ */
.lawyer-chat-container *,
.lawyer-chat-container *::before,
.lawyer-chat-container *::after { box-sizing: border-box; margin: 0; padding: 0; }

.lawyer-chat-container {
    position: static;
    z-index: 999999;
    direction: rtl;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* ================================================================
   FLOATING ACTION BUTTON
   ================================================================ */
.lawyer-toggle-btn {
    position: fixed;
    bottom: 24px;
    left: 24px;
    background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
    color: #f59e0b;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    box-shadow: 0 8px 28px rgba(0,0,0,.38), 0 0 0 0 rgba(245,158,11,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: grab;
    border: 2px solid rgba(245,158,11,.35);
    z-index: 999999;
    outline: none;
    user-select: none;
    transition: transform .25s ease, box-shadow .25s ease, background .25s;
}
.lawyer-toggle-btn:active  { cursor: grabbing; }
.lawyer-toggle-btn:hover {
    transform: scale(1.09);
    box-shadow: 0 14px 36px rgba(0,0,0,.42), 0 0 0 5px rgba(245,158,11,.18);
}
.lawyer-toggle-btn i,
.lawyer-toggle-btn svg {
    font-size: 24px;
    width: 24px;
    height: 24px;
    color: #f59e0b;
    pointer-events: none;
    flex-shrink: 0;
    line-height: 1;
    /* No display:flex here — conflicts with Font Awesome SVG injection */
}

/* ================================================================
   CHAT WIDGET SHELL
   ================================================================ */
.lawyer-chat-widget {
    position: fixed;
    width: 760px;
    max-width: 96vw;
    height: 595px;
    max-height: 84vh;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 28px 70px rgba(0,0,0,.22), 0 8px 24px rgba(0,0,0,.12);
    border: 1px solid rgba(226,232,240,.9);
    display: flex;
    overflow: hidden;
    z-index: 999998;
    opacity: 1;
    visibility: visible;
    transform: scale(1) translateY(0);
    transform-origin: bottom left;
    transition: opacity .22s ease, transform .26s cubic-bezier(.34,1.56,.64,1), visibility .22s;
}
.lawyer-chat-widget.collapsed {
    opacity: 0;
    visibility: hidden;
    transform: scale(.91) translateY(14px);
    pointer-events: none;
}

/* ================================================================
   SIDEBAR
   ================================================================ */
.widget-sidebar {
    width: 228px;
    background: linear-gradient(180deg, #0f172a 0%, #0c1220 100%);
    display: flex;
    flex-direction: column;
    border-left: 1px solid rgba(255,255,255,.05);
    padding: 14px 10px 10px;
    flex-shrink: 0;
    overflow: hidden;
}

.btn-new-chat {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #fff !important;
    border: none;
    border-radius: 11px;
    padding: 11px 14px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 16px;
    cursor: pointer;
    width: 100%;
    font-family: inherit;
    letter-spacing: .3px;
    box-shadow: 0 4px 16px rgba(180,83,9,.3);
    transition: all .2s;
}
.btn-new-chat:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 6px 22px rgba(245,158,11,.35);
    transform: translateY(-1px);
}
.btn-new-chat:active { transform: translateY(0); }

.sidebar-label {
    color: rgba(148,163,184,.55);
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.3px;
    margin-bottom: 10px;
    padding: 0 4px;
}

.sidebar-sessions-list {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 4px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.08) transparent;
}
.sidebar-sessions-list::-webkit-scrollbar { width: 4px; }
.sidebar-sessions-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

.sidebar-session-item {
    padding: 9px 10px;
    font-size: 11px;
    color: #94a3b8;
    border-radius: 9px;
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    transition: all .18s;
    background: rgba(255,255,255,.02);
    border: 1px solid rgba(255,255,255,.04);
    min-height: 38px;
    position: relative;
    user-select: none;
}
.sidebar-session-item:hover {
    background: rgba(245,158,11,.08);
    color: #fbbf24;
    border-color: rgba(245,158,11,.14);
}
.sidebar-session-item.active {
    background: linear-gradient(135deg, rgba(245,158,11,.14), rgba(217,119,6,.08));
    color: #f59e0b;
    border-color: rgba(245,158,11,.26);
    font-weight: 600;
}
.session-icon { font-size: 11px; flex-shrink: 0; opacity: .7; }
.sidebar-session-item.active .session-icon { opacity: 1; }
.session-title {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 11px;
}
.session-delete-btn {
    background: none;
    border: none;
    color: rgba(148,163,184,.35);
    cursor: pointer;
    padding: 3px 5px;
    border-radius: 5px;
    font-size: 10px;
    opacity: 0;
    transition: all .15s;
    flex-shrink: 0;
    line-height: 1;
}
.sidebar-session-item:hover .session-delete-btn { opacity: 1; }
.session-delete-btn:hover { color: #f87171; background: rgba(239,68,68,.12); }

.no-sessions-msg {
    color: rgba(100,116,139,.55);
    font-size: 10px;
    text-align: center;
    padding: 24px 8px;
    line-height: 1.6;
}

/* ================================================================
   MAIN CHAT AREA
   ================================================================ */
.widget-main-chat {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
    min-width: 0;
}

/* ── Header ─────────────────────────────────────────────────────── */
.widget-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #fff;
    padding: 11px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255,255,255,.06);
    flex-shrink: 0;
    gap: 10px;
}
.widget-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.header-avatar {
    width: 38px; height: 38px;
    background: rgba(245,158,11,.12);
    border: 1.5px solid rgba(245,158,11,.3);
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
    color: #f59e0b;
}
.widget-header-info h2 {
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .2px;
    white-space: nowrap;
}
.widget-header-info p {
    font-size: 9.5px;
    color: #94a3b8; /* مُزال: مؤشر الاتصال الأخضر — الآن رمادي محايد */
    margin-top: 2px;
    white-space: nowrap;
}

/* Model Switcher */
.model-switcher {
    display: flex;
    background: rgba(255,255,255,.07);
    border-radius: 22px;
    padding: 3px;
    gap: 2px;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,.09);
}
.model-pill {
    background: none;
    border: none;
    color: rgba(255,255,255,.45);
    font-size: 10px;
    font-weight: 600;
    padding: 4px 11px;
    border-radius: 16px;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    font-family: inherit;
    letter-spacing: .3px;
}
.model-pill.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #0f172a;
    box-shadow: 0 2px 8px rgba(245,158,11,.32);
}
.model-pill:hover:not(.active) { color: rgba(255,255,255,.85); background: rgba(255,255,255,.11); }

/* ── Chat Box ────────────────────────────────────────────────────── */
.widget-chat-box {
    flex: 1;
    background: #f8fafc;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    position: relative;
    scrollbar-width: thin;
    scrollbar-color: #dde3ec transparent;
}
.widget-chat-box::-webkit-scrollbar { width: 5px; }
.widget-chat-box::-webkit-scrollbar-thumb { background: #dde3ec; border-radius: 3px; }

.chat-messages-wrapper {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    margin-top: auto;
}

/* Drop Overlay */
.drop-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15,23,42,.94);
    color: #f59e0b;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    font-size: 14px;
    font-weight: 600;
    z-index: 100;
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s;
}
.widget-chat-box.dragover .drop-overlay { opacity: 1; }

/* ── Message Animations ──────────────────────────────────────────── */
@keyframes msgSlideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.msg-appear { animation: msgSlideUp .24s ease forwards; }

/* ── User Messages ───────────────────────────────────────────────── */
.msg-user-wrapper {
    display: flex;
    flex-direction: column;
    align-self: flex-end;
    align-items: flex-end;
    max-width: 80%;
    gap: 3px;
}
.msg-user {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #fff;
    padding: 10px 14px;
    border-radius: 16px 16px 3px 16px;
    font-size: 12px;
    line-height: 1.58;
    display: flex;
    flex-direction: column;
    gap: 6px;
    box-shadow: 0 2px 10px rgba(180,83,9,.22);
    width: fit-content;
    max-width: 100%;
    word-break: break-word;
}
.msg-timestamp {
    font-size: 9px;
    color: rgba(255,255,255,.55);
    text-align: left;
    margin-top: 1px;
    direction: ltr;
}

/* Edit button on last user message */
.msg-edit-btn {
    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.22);
    color: rgba(255,255,255,.75);
    border-radius: 7px;
    padding: 3px 9px;
    font-size: 10px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all .15s;
    font-family: inherit;
}
.msg-edit-btn:hover { background: rgba(255,255,255,.23); color: #fff; }

/* ── Bot Messages ────────────────────────────────────────────────── */
.msg-bot {
    align-self: flex-start;
    background: #fff;
    color: #1e293b;
    padding: 10px 14px;
    border-radius: 16px 16px 16px 3px;
    font-size: 12px;
    max-width: 84%;
    border: 1px solid #e2e8f0;
    border-right: 3px solid #f59e0b;
    line-height: 1.62;
    box-shadow: 0 1px 5px rgba(0,0,0,.06);
    word-break: break-word;
}
.msg-bot .msg-timestamp { color: #94a3b8; text-align: right; margin-top: 6px; }
.msg-bot strong  { font-weight: 700; color: #0f172a; }
.msg-bot em      { font-style: italic; color: #475569; }
.msg-bot code    { background: #f1f5f9; padding: 1px 6px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 11px; color: #b45309; }
.msg-bot ul, .msg-bot ol { margin: 6px 0; padding-right: 20px; }
.msg-bot li      { margin-bottom: 3px; }
.msg-bot h3, .msg-bot h4 { margin: 8px 0 4px; color: #0f172a; }
.msg-bot h3 { font-size: 13px; }
.msg-bot h4 { font-size: 12px; }

/* ── File Attachment in message ──────────────────────────────────── */
.msg-file-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.14);
    padding: 7px 10px;
    border-radius: 9px;
    border: 1px solid rgba(255,255,255,.2);
    text-decoration: none;
    color: #fff !important;
    max-width: 100%;
}
.msg-bot .msg-file-box { background: #f1f5f9; border-color: #e2e8f0; color: #334155 !important; }
.msg-file-box i { font-size: 16px; color: #fef3c7; flex-shrink: 0; }
.msg-bot .msg-file-box i { color: #d97706; }
.msg-file-info { display: flex; flex-direction: column; overflow: hidden; }
.msg-file-name { font-size: 10px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.msg-text-content { word-break: break-word; }

/* ── Welcome Card ────────────────────────────────────────────────── */
.welcome-card {
    align-self: center;
    text-align: center;
    padding: 24px 20px;
    width: 100%;
    max-width: 380px;
    margin-top: auto;
    margin-bottom: 8px;
}
.welcome-icon {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 24px;
    box-shadow: 0 6px 18px rgba(245,158,11,.22);
}
.welcome-card h3 { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.welcome-card p  { font-size: 11px; color: #64748b; line-height: 1.55; margin-bottom: 18px; }
.welcome-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    justify-content: center;
}
.suggestion-chip {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 20px;
    padding: 6px 13px;
    font-size: 10.5px;
    color: #334155;
    cursor: pointer;
    transition: all .18s;
    font-family: inherit;
    white-space: nowrap;
}
.suggestion-chip:hover { border-color: #f59e0b; color: #b45309; background: #fffbeb; box-shadow: 0 2px 8px rgba(245,158,11,.14); }

/* ── Typing Indicator ────────────────────────────────────────────── */
.loading-skeleton {
    display: none;
    align-self: flex-start;
    align-items: center;
    gap: 10px;
}
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-right: 3px solid #f59e0b;
    border-radius: 16px 16px 16px 3px;
    padding: 11px 16px;
    box-shadow: 0 1px 5px rgba(0,0,0,.06);
}
.typing-indicator span {
    width: 7px; height: 7px;
    background: #d97706;
    border-radius: 50%;
    animation: typingBounce 1.3s ease-in-out infinite;
}
.typing-indicator span:nth-child(2) { animation-delay: .16s; }
.typing-indicator span:nth-child(3) { animation-delay: .32s; }
@keyframes typingBounce {
    0%,60%,100% { transform: translateY(0); opacity: .35; }
    30%          { transform: translateY(-6px); opacity: 1; }
}

/* ================================================================
   VOICE RECORDING BAR
   ================================================================ */
.voice-recording-bar {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border-top: 1px solid rgba(245,158,11,.18);
    flex-shrink: 0;
}
.rec-dot {
    width: 10px; height: 10px;
    background: #ef4444;
    border-radius: 50%;
    flex-shrink: 0;
    animation: recPulse 1s ease-in-out infinite;
}
@keyframes recPulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .45; transform: scale(.78); }
}
.rec-label {
    font-size: 10px; color: #f87171; font-weight: 600;
    flex-shrink: 0; white-space: nowrap;
}
#lawyer-chat-waveform {
    flex: 1;
    height: 42px;
    border-radius: 7px;
    background: rgba(255,255,255,.04);
    display: block;
}
.rec-timer {
    font-size: 11px; color: #94a3b8; font-weight: 500;
    flex-shrink: 0; min-width: 30px;
    font-variant-numeric: tabular-nums;
    direction: ltr;
}
.stop-rec-btn {
    background: rgba(239,68,68,.16);
    border: 1px solid rgba(239,68,68,.3);
    color: #f87171;
    width: 32px; height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s;
    font-size: 11px;
    flex-shrink: 0;
}
.stop-rec-btn:hover { background: rgba(239,68,68,.28); }

/* ================================================================
   VOICE CONTROLS BAR (after recording stops)
   ================================================================ */
.voice-controls-bar {
    display: none;
    align-items: center;
    justify-content: space-between;
    padding: 9px 16px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    gap: 10px;
    flex-shrink: 0;
}
.voice-preview-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: #334155;
    font-weight: 500;
    flex: 1;
    overflow: hidden;
}
.voice-preview-info i { color: #d97706; flex-shrink: 0; }
.voice-actions { display: flex; gap: 6px; flex-shrink: 0; }
.voice-ctrl-btn {
    border: none;
    border-radius: 9px;
    padding: 7px 13px;
    font-size: 10.5px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all .15s;
    font-family: inherit;
}
.vcb-send   { background: linear-gradient(135deg,#d97706,#b45309); color:#fff; box-shadow: 0 2px 8px rgba(180,83,9,.25); }
.vcb-send:hover   { background: linear-gradient(135deg,#f59e0b,#d97706); transform: translateY(-1px); }
.vcb-retry  { background: rgba(59,130,246,.1); color: #3b82f6; border: 1px solid rgba(59,130,246,.2); }
.vcb-retry:hover  { background: rgba(59,130,246,.18); }
.vcb-delete { background: rgba(239,68,68,.08); color: #ef4444; border: 1px solid rgba(239,68,68,.16); }
.vcb-delete:hover { background: rgba(239,68,68,.16); }

/* ================================================================
   FILE PREVIEW
   ================================================================ */
.preview-container {
    display: none;
    padding: 8px 16px;
    background: #f1f5f9;
    border-top: 1px solid #e2e8f0;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-shrink: 0;
}
.preview-file-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: #334155;
    font-weight: 500;
    overflow: hidden;
    min-width: 0;
}
.preview-file-info i { color: #d97706; flex-shrink: 0; }
.preview-file-info span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.preview-remove-btn {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 13px;
    padding: 3px 6px;
    border-radius: 5px;
    transition: background .15s;
    flex-shrink: 0;
}
.preview-remove-btn:hover { background: rgba(239,68,68,.1); }

/* ================================================================
   FOOTER / INPUT
   ================================================================ */
.widget-footer {
    padding: 10px 12px;
    background: #fff;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.widget-form { display: flex; gap: 6px; align-items: center; }
.widget-input {
    flex: 1;
    background: #f1f5f9;
    border: 1.5px solid #e2e8f0;
    border-radius: 11px;
    padding: 9px 13px;
    font-size: 12px;
    outline: none;
    color: #1e293b;
    font-family: inherit;
    transition: all .18s;
    min-width: 0;
}
.widget-input:focus { border-color: #f59e0b; background: #fff; box-shadow: 0 0 0 3px rgba(245,158,11,.1); }
.widget-input::placeholder { color: #94a3b8; }
.widget-input:disabled { opacity: .5; cursor: not-allowed; }

.widget-action-btn {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 15px;
    cursor: pointer;
    padding: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    border-radius: 9px;
    flex-shrink: 0;
}
.widget-action-btn:hover { color: #d97706; background: rgba(245,158,11,.08); }

.widget-send-btn {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: #f59e0b;
    width: 37px; height: 37px;
    border-radius: 11px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .18s;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(15,23,42,.3);
}
.widget-send-btn:hover { background: linear-gradient(135deg,#334155,#1e293b); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(15,23,42,.36); }
.widget-send-btn:active { transform: translateY(0); }
.widget-send-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

/* ================================================================
   SESSION LOADING STATE
   ================================================================ */
.session-load-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
    color: #94a3b8;
    gap: 14px;
    flex: 1;
}
.session-load-state i { font-size: 22px; color: #d97706; }
.session-load-state span { font-size: 11px; }
</style>

{{-- ================================================================
     HTML STRUCTURE
     ================================================================ --}}
<div class="lawyer-chat-container">

    {{-- Floating Action Button --}}
    <button id="lawyer-chat-toggle-btn" class="lawyer-toggle-btn" aria-label="فتح المساعد القانوني" type="button">
        <i id="lawyer-chat-toggle-icon" class="fa-solid fa-scale-balanced"></i>
    </button>

    {{-- Chat Widget --}}
    <div id="lawyer-chat-widget-box" class="lawyer-chat-widget collapsed" role="dialog" aria-label="المساعد القانوني الذكي">

        {{-- ── Sidebar ─────────────────────────────────────── --}}
        <div class="widget-sidebar">
            <button id="btn-new-chat" class="btn-new-chat" type="button">
                <i class="fa-solid fa-plus"></i> محادثة جديدة
            </button>

            <div class="sidebar-label">المحادثات السابقة</div>

            <div class="sidebar-sessions-list" id="sidebar-sessions-list">
                @php
                    $currentId = isset($id) ? $id : (isset($session) ? $session->id : 0);
                @endphp
                @if(isset($sessions) && $sessions->count() > 0)
                    @foreach($sessions as $s)
                        <div class="sidebar-session-item {{ $currentId == $s->id ? 'active' : '' }}"
                             data-session-id="{{ $s->id }}"
                             role="button"
                             tabindex="0"
                             title="{{ $s->title ?? 'محادثة رقم ' . $s->id }}">
                            <i class="fa-regular fa-comments session-icon"></i>
                            <span class="session-title">{{ $s->title ?? 'محادثة رقم ' . $s->id }}</span>
                            <button class="session-delete-btn"
                                    data-session-id="{{ $s->id }}"
                                    title="حذف المحادثة"
                                    type="button">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="no-sessions-msg" id="no-sessions-msg">لا توجد محادثات سابقة</div>
                @endif
            </div>
        </div>

        {{-- ── Main Chat ───────────────────────────────────── --}}
                    @if(!isset($messages) || $messages->isEmpty())
                        {{-- Welcome Card --}}
                        <div class="welcome-card" id="welcome-card">
                            <div class="welcome-icon" style="font-size:32px;">
                                ⚖️
                            </div>
                            <h3>مرحباً بك في المساعد القانوني الذكي</h3>
                            <p>أنا مساعدك القانوني الذكي — أصوغ العقود، أراجع البنود، وأدعمك في أي إجراءات قانونية حول العالم.</p>
                            <div class="welcome-suggestions">
                                <button class="suggestion-chip" type="button" onclick="fillSuggestion('صغ لي عقد إيجار سكني وفق أحدث القوانين'); submitMessage();">📝 صياغة عقد إيجار</button>
                                <button class="suggestion-chip" type="button" onclick="fillSuggestion('ما هي إجراءات رفع دعوى مدنية؟ حدد الاختصاص القضائي إن أمكن'); submitMessage();">⚖️ إجراءات الدعوى</button>
                                <button class="suggestion-chip" type="button" onclick="fillSuggestion('راجع هذا البند القانوني وأبدِ رأيك القانوني المفصَّل فيه'); submitMessage();">🔍 مراجعة بند قانوني</button>
                            </div>
                        </div>
                    @else
                        {{-- PHP-rendered session messages --}}
                        @foreach($messages as $msg)
                            @if($msg->role == 'user')
                                <div class="msg-user-wrapper">
                                    <div class="msg-user">
                                        @if(!empty($msg->file_path))
                                            <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="msg-file-box">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <div class="msg-file-info">
                                                    <span class="msg-file-name">{{ $msg->file_name ?? 'عرض المستند' }}</span>
                                                </div>
                                            </a>
                                        @endif
                                        @if(!empty($msg->message))
                                            <div class="msg-text-content">{{ $msg->message }}</div>
                                        @endif
                                        <div class="msg-timestamp">{{ $msg->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="msg-bot">
                                    <div class="msg-text-content">{!! nl2br(e($msg->message)) !!}</div>
                                    <div class="msg-timestamp">{{ $msg->created_at->format('H:i') }}</div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Typing indicator --}}
                    <div id="loading-skeleton" class="loading-skeleton">
                        <div class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Voice Recording Bar --}}
            <div id="voice-recording-bar" class="voice-recording-bar" aria-live="polite">
                <div class="rec-dot" aria-hidden="true"></div>
                <span class="rec-label">● تسجيل</span>
                <canvas id="lawyer-chat-waveform" aria-hidden="true"></canvas>
                <span class="rec-timer" id="rec-timer">0:00</span>
                <button id="stop-rec-btn" class="stop-rec-btn" type="button" title="إيقاف التسجيل">
                    <i class="fa-solid fa-stop"></i>
                </button>
            </div>

            {{-- Voice Controls Bar --}}
            <div id="voice-controls-bar" class="voice-controls-bar">
                <div class="voice-preview-info">
                    <i class="fa-solid fa-waveform-lines"></i>
                    <span>تسجيل صوتي جاهز للإرسال</span>
                </div>
                <div class="voice-actions">
                    <button id="lawyer-chat-voice-send-btn"   class="voice-ctrl-btn vcb-send"   type="button"><i class="fa-solid fa-paper-plane"></i> إرسال</button>
                    <button id="lawyer-chat-voice-retry-btn"  class="voice-ctrl-btn vcb-retry"  type="button"><i class="fa-solid fa-rotate-right"></i> إعادة</button>
                    <button id="lawyer-chat-voice-delete-btn" class="voice-ctrl-btn vcb-delete" type="button"><i class="fa-solid fa-trash"></i> حذف</button>
                </div>
            </div>

            {{-- File Preview --}}
            <div class="preview-container" id="preview-container">
                <div class="preview-file-info">
                    <i id="lawyer-chat-preview-icon" class="fa-solid fa-file"></i>
                    <span id="lawyer-chat-preview-filename">اسم الملف.pdf</span>
                </div>
                <button type="button" class="preview-remove-btn" id="preview-remove-btn" title="إزالة المرفق">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>

            {{-- Footer --}}
            <div class="widget-footer">
                <div id="lawyer-chat-form" class="widget-form">
                    <input type="hidden" id="lawyer-chat-session-id"
                        value="{{ isset($id) ? $id : (isset($session) ? $session->id : '') }}">

                    <button type="button" id="lawyer-chat-file-btn" class="widget-action-btn" title="إرفاق ملف أو مستند">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                    <input type="file" id="lawyer-chat-file-input" style="display:none"
                        accept=".pdf,.doc,.docx,.txt,image/*">

                    <button type="button" id="lawyer-chat-voice-btn" class="widget-action-btn" title="تسجيل رسالة صوتية">
                        <i class="fa-solid fa-microphone" id="lawyer-chat-voice-icon"></i>
                    </button>

                    <input type="text" id="lawyer-chat-message-input" autocomplete="off"
                        placeholder="اكتب سؤالك القانوني هنا..." class="widget-input"
                        aria-label="رسالتك">

                    <button type="button" id="lawyer-chat-submit-btn" class="widget-send-btn" title="إرسال">
                        <i class="fa-solid fa-paper-plane" style="transform:rotate(180deg)"></i>
                    </button>
                </div>
            </div>

        </div>{{-- /.widget-main-chat --}}
    </div>{{-- /#lawyer-chat-widget-box --}}
</div>{{-- /.lawyer-chat-container --}}


<script>
(function() {
/* ================================================================
   CONFIGURATION (Blade → JS bridge)
   ================================================================ */
const LAWYER_CHAT_ROUTES = {
    send:           '{{ route("chat.send") }}',
    sessionBase:    '{{ url("/chat/session") }}',
    chatBase:       '{{ url("/chat") }}',
};
const LAWYER_CHAT_STORAGE = '{{ asset("storage") }}';
const LAWYER_CHAT_CSRF  = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

@php
$initialHistory = [];
if (isset($messages) && !$messages->isEmpty()) {
    foreach ($messages as $msg) {
        $initialHistory[] = [
            'role'    => $msg->role === 'model' ? 'assistant' : 'user',
            'content' => $msg->message ?? ''
        ];
    }
}
@endphp
let chatHistory = @json($initialHistory);

/* ================================================================
   DOM REFERENCES
   ================================================================ */
const widgetToggleBtn    = document.getElementById('lawyer-chat-toggle-btn');
const chatWidget         = document.getElementById('lawyer-chat-widget-box');
const toggleIcon         = document.getElementById('lawyer-chat-toggle-icon');
const sessionIdInput     = document.getElementById('lawyer-chat-session-id');
const messageInput       = document.getElementById('lawyer-chat-message-input');
const submitSendBtn      = document.getElementById('lawyer-chat-submit-btn');
const chatBox            = document.getElementById('lawyer-chat-box');
const messagesWrapper    = document.getElementById('chat-messages-wrapper');
const sessionsList       = document.getElementById('sidebar-sessions-list');
const fileTriggerBtn     = document.getElementById('lawyer-chat-file-btn');
const fileInput          = document.getElementById('lawyer-chat-file-input');
const voiceRecordBtn     = document.getElementById('lawyer-chat-voice-btn');
const voiceRecordingBar  = document.getElementById('voice-recording-bar');
const voiceControlsBar   = document.getElementById('voice-controls-bar');
const voiceSendBtn       = document.getElementById('lawyer-chat-voice-send-btn');
const voiceRetryBtn      = document.getElementById('lawyer-chat-voice-retry-btn');
const voiceDeleteBtn     = document.getElementById('lawyer-chat-voice-delete-btn');
const stopRecBtn         = document.getElementById('stop-rec-btn');
const waveformCanvas     = document.getElementById('lawyer-chat-waveform');
const recTimerEl         = document.getElementById('rec-timer');
const previewContainer   = document.getElementById('preview-container');
const previewFilename    = document.getElementById('lawyer-chat-preview-filename');
const previewIcon        = document.getElementById('lawyer-chat-preview-icon');
const previewRemoveBtn   = document.getElementById('preview-remove-btn');

let loadingSkeleton      = document.getElementById('loading-skeleton');

/* ================================================================
   STATE
   ================================================================ */
let selectedModel        = 'gpt-4o-mini';
let currentPendingFile   = null;
let currentPendingVoice  = null;
let lastSentText         = '';
let lastSentFile         = null;
let lastSentVoice        = null;
let lastMsgWrapper       = null;

// Voice recording
let mediaRecorder        = null;
let audioChunks          = [];
let isRecording          = false;
let pendingVoiceBlob     = null;
let audioCtx             = null;
let analyserNode         = null;
let micSource            = null;
let waveformAnimId       = null;
let recSeconds           = 0;
let recTimerInterval     = null;

/* ================================================================
   UTILITIES
   ================================================================ */
function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function formatTime(secs) {
    const m = Math.floor(secs / 60);
    const s = secs % 60;
    return `${m}:${String(s).padStart(2,'0')}`;
}

function nowTime() {
    return new Date().toLocaleTimeString('ar-EG', {hour:'2-digit', minute:'2-digit'});
}

/**
 * Lightweight Markdown → HTML renderer for bot messages.
 */
function renderMarkdown(raw) {
    if (!raw) return '';
    // Escape HTML first
    let t = raw
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;');
    // Headings
    t = t.replace(/^### (.+)$/gm, '<h4>$1</h4>');
    t = t.replace(/^## (.+)$/gm,  '<h3>$1</h3>');
    t = t.replace(/^# (.+)$/gm,   '<h3>$1</h3>');
    // Bold & Italic
    t = t.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
    t = t.replace(/\*\*(.+?)\*\*/g,     '<strong>$1</strong>');
    t = t.replace(/\*([^\*\n]+?)\*/g,   '<em>$1</em>');
    // Inline code
    t = t.replace(/`([^`\n]+?)`/g, '<code>$1</code>');
    // Unordered lists
    t = t.replace(/^[\-\*\•] (.+)$/gm, '<li>$1</li>');
    // Ordered lists
    t = t.replace(/^\d+[\.\)] (.+)$/gm, '<li>$1</li>');
    // Wrap consecutive list items
    t = t.replace(/((<li>.*?<\/li>\n?)+)/gs, '<ul>$1</ul>');
    // Paragraph breaks
    t = t.replace(/\n\n+/g, '<br><br>');
    t = t.replace(/\n/g,    '<br>');
    return t;
}

function fillSuggestion(text) {
    messageInput.value = text;
    messageInput.focus();
    // Move cursor to end
    messageInput.setSelectionRange(text.length, text.length);
    // Hide welcome card smoothly
    const wc = document.getElementById('welcome-card');
    if (wc) { wc.style.transition = 'opacity .2s'; wc.style.opacity = '0'; setTimeout(()=>{ if(wc.parentNode) wc.remove(); }, 200); }
}

function submitMessage() {
    performMessageSend(false);
}

// Expose on window for inline HTML onclick handlers
window.fillSuggestion = fillSuggestion;
window.submitMessage  = submitMessage;

/* ================================================================
   CREATE WELCOME CARD (for SPA new chat)
   ================================================================ */
function createWelcomeCard() {
    const div = document.createElement('div');
    div.id        = 'welcome-card';
    div.className = 'welcome-card';
    div.innerHTML = `
        <div class="welcome-icon" style="font-size:32px;">⚖️</div>
        <h3>مرحباً بك في المساعد القانوني الذكي</h3>
        <p>أنا مساعدك القانوني الذكي — أصوغ العقود، أراجع البنود، وأدعمك في أي إجراءات قانونية حول العالم.</p>
        <div class="welcome-suggestions">
            <button class="suggestion-chip" type="button" onclick="fillSuggestion('صغ لي عقد إيجار سكني وفق أحدث القوانين'); submitMessage();">📝 صياغة عقد إيجار</button>
            <button class="suggestion-chip" type="button" onclick="fillSuggestion('ما هي إجراءات رفع دعوى مدنية؟ حدد الاختصاص القضائي إن أمكن'); submitMessage();">⚖️ إجراءات الدعوى</button>
            <button class="suggestion-chip" type="button" onclick="fillSuggestion('راجع هذا البند القانوني وأبدِ رأيك القانوني المفصَّل فيه'); submitMessage();">🔍 مراجعة بند قانوني</button>
        </div>`;
    return div;
}

/* ================================================================
   MESSAGE ELEMENT BUILDERS
   ================================================================ */
function createUserMessageEl(msg) {
    const wrapper = document.createElement('div');
    wrapper.className = 'msg-user-wrapper msg-appear';

    const bubble = document.createElement('div');
    bubble.className = 'msg-user';

    if (msg.file_path && msg.file_name) {
        const ext = (msg.file_name || '').split('.').pop().toLowerCase();
        let ico = 'fa-solid fa-file-lines';
        if (ext === 'pdf') ico = 'fa-solid fa-file-pdf';
        else if (['jpg','jpeg','png','gif','webp'].includes(ext)) ico = 'fa-solid fa-file-image';
        else if (['mp3','wav','webm','ogg','m4a'].includes(ext)) ico = 'fa-solid fa-file-audio';

        const fileUrl = msg.file_path.startsWith('http') ? msg.file_path : `${LAWYER_CHAT_STORAGE}/${msg.file_path}`;
        const fb = document.createElement('a');
        fb.href      = fileUrl;
        fb.target    = '_blank';
        fb.className = 'msg-file-box';
        fb.innerHTML = `<i class="${ico}"></i><div class="msg-file-info"><span class="msg-file-name">${escapeHtml(msg.file_name)}</span></div>`;
        bubble.appendChild(fb);
    }

    if (msg.message) {
        const textEl = document.createElement('div');
        textEl.className   = 'msg-text-content';
        textEl.textContent = msg.message;
        bubble.appendChild(textEl);
    }

    const ts = document.createElement('div');
    ts.className   = 'msg-timestamp';
    ts.textContent = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('ar-EG',{hour:'2-digit',minute:'2-digit'}) : nowTime();
    bubble.appendChild(ts);

    wrapper.appendChild(bubble);
    return wrapper;
}

function createBotMessageEl(msg) {
    const div = document.createElement('div');
    div.className = 'msg-bot msg-appear';

    const content = document.createElement('div');
    content.className = 'msg-text-content';
    content.innerHTML = renderMarkdown(msg.message || '');
    div.appendChild(content);

    const ts = document.createElement('div');
    ts.className   = 'msg-timestamp';
    ts.textContent = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('ar-EG',{hour:'2-digit',minute:'2-digit'}) : '';
    div.appendChild(ts);

    return div;
}

/* ================================================================
   EDIT LAST USER MESSAGE
   ================================================================ */
function refreshEditButton() {
    // Remove any existing edit buttons
    document.querySelectorAll('.msg-edit-btn').forEach(b => b.remove());

    const wrappers = messagesWrapper.querySelectorAll('.msg-user-wrapper');
    if (!wrappers.length) return;

    const last = wrappers[wrappers.length - 1];
    const btn  = document.createElement('button');
    btn.className = 'msg-edit-btn';
    btn.type      = 'button';
    btn.title     = 'تعديل الرسالة';
    btn.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> تعديل';

    btn.addEventListener('click', () => {
        // Restore text to input
        const textEl = last.querySelector('.msg-text-content');
        if (textEl) messageInput.value = textEl.textContent;

        // Remove the last user wrapper + everything after it up to loading-skeleton
        const children = Array.from(messagesWrapper.children);
        const idx      = children.indexOf(last);
        for (let i = idx; i < children.length; i++) {
            if (children[i] === loadingSkeleton) break;
            children[i].remove();
        }

        // Trim chatHistory: remove last assistant reply, then last user message
        if (chatHistory.length && chatHistory[chatHistory.length - 1].role === 'assistant') chatHistory.pop();
        if (chatHistory.length && chatHistory[chatHistory.length - 1].role === 'user')      chatHistory.pop();

        refreshEditButton();
        messageInput.focus();
    });

    last.appendChild(btn);
}

/* ================================================================
   SIDEBAR HELPERS
   ================================================================ */
function addSessionToSidebar(sessionId, title) {
    const noMsg = document.getElementById('no-sessions-msg');
    if (noMsg) noMsg.remove();

    if (document.querySelector(`.sidebar-session-item[data-session-id="${sessionId}"]`)) return;

    // Deactivate all
    document.querySelectorAll('.sidebar-session-item').forEach(el => el.classList.remove('active'));

    const item = document.createElement('div');
    item.className        = 'sidebar-session-item active msg-appear';
    item.dataset.sessionId = String(sessionId);
    item.setAttribute('role','button');
    item.setAttribute('tabindex','0');
    item.innerHTML = `
        <i class="fa-regular fa-comments session-icon"></i>
        <span class="session-title">${escapeHtml(title)}</span>
        <button class="session-delete-btn" data-session-id="${sessionId}" title="حذف المحادثة" type="button">
            <i class="fa-solid fa-trash"></i>
        </button>`;

    sessionsList.insertBefore(item, sessionsList.firstChild);
    attachSessionItemEvents(item);
}

function attachSessionItemEvents(item) {
    item.addEventListener('click', e => {
        if (e.target.closest('.session-delete-btn')) return;
        loadSessionMessages(item.dataset.sessionId);
    });
    item.addEventListener('keydown', e => {
        if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.session-delete-btn')) {
            loadSessionMessages(item.dataset.sessionId);
        }
    });

    const delBtn = item.querySelector('.session-delete-btn');
    if (delBtn) {
        delBtn.addEventListener('click', e => {
            e.stopPropagation();
            deleteSession(delBtn.dataset.sessionId, item);
        });
    }
}

async function loadSessionMessages(sessionId) {
    sessionIdInput.value = String(sessionId);
    chatHistory = [];

    // Update active UI
    document.querySelectorAll('.sidebar-session-item').forEach(el =>
        el.classList.toggle('active', el.dataset.sessionId === String(sessionId))
    );

    // Clear and show loading placeholder
    clearMessages();
    const placeholder = document.createElement('div');
    placeholder.className = 'session-load-state';
    placeholder.id        = 'session-load-placeholder';
    placeholder.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>جاري تحميل المحادثة...</span>';
    messagesWrapper.insertBefore(placeholder, loadingSkeleton);

    try {
        const res  = await fetch(`${LAWYER_CHAT_ROUTES.sessionBase}/${sessionId}/messages`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': LAWYER_CHAT_CSRF }
        });
        const data = await res.json();

        clearMessages(); // removes placeholder too

        if (!data.messages || data.messages.length === 0) {
            messagesWrapper.insertBefore(createWelcomeCard(), loadingSkeleton);
        } else {
            data.messages.forEach(msg => {
                const el = msg.role === 'user' ? createUserMessageEl(msg) : createBotMessageEl(msg);
                messagesWrapper.insertBefore(el, loadingSkeleton);
                chatHistory.push({
                    role:    msg.role === 'model' ? 'assistant' : 'user',
                    content: msg.message || ''
                });
            });
            refreshEditButton();
        }
        scrollToBottom();
    } catch (err) {
        clearMessages();
        const errDiv = document.createElement('div');
        errDiv.className = 'session-load-state';
        errDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="color:#ef4444"></i><span style="color:#ef4444">خطأ في تحميل المحادثة</span>';
        messagesWrapper.insertBefore(errDiv, loadingSkeleton);
    }
}

async function deleteSession(sessionId, itemEl) {
    if (!confirm('هل تريد حذف هذه المحادثة نهائياً؟')) return;
    try {
        const res = await fetch(`${LAWYER_CHAT_ROUTES.chatBase}/${sessionId}`, {
            method:  'DELETE',
            headers: {
                'X-CSRF-TOKEN':     LAWYER_CHAT_CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept':           'application/json',
            }
        });
        const data = await res.json();
        if (data.success) {
            // If this was the active session, go to new chat
            if (sessionIdInput.value === String(sessionId)) triggerNewChat();
            itemEl.style.animation = 'none';
            itemEl.style.opacity   = '0';
            setTimeout(() => {
                itemEl.remove();
                if (!document.querySelector('.sidebar-session-item')) {
                    sessionsList.innerHTML = '<div class="no-sessions-msg" id="no-sessions-msg">لا توجد محادثات سابقة</div>';
                }
            }, 200);
        }
    } catch(e) {
        alert('فشل حذف المحادثة. حاول مجدداً.');
    }
}

function clearMessages() {
    Array.from(messagesWrapper.children).forEach(c => {
        if (c !== loadingSkeleton) c.remove();
    });
}

/* ================================================================
   SPA — NEW CHAT
   ================================================================ */
function triggerNewChat() {
    sessionIdInput.value = '';
    chatHistory          = [];
    clearMessages();
    messagesWrapper.insertBefore(createWelcomeCard(), loadingSkeleton);
    document.querySelectorAll('.sidebar-session-item').forEach(el => el.classList.remove('active'));
    resetPendingFiles();
    messageInput.focus();
}

document.getElementById('btn-new-chat').addEventListener('click', triggerNewChat);

/* ================================================================
   MODEL SWITCHER
   ================================================================ */
document.querySelectorAll('.model-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.model-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        selectedModel = pill.dataset.model;
    });
});

/* ================================================================
   FAB DRAG & DROP
   ================================================================ */
let isDragging = false, dragStartX, dragStartY, initLeft, initTop, movedEnough = false;

function startDrag(e) {
    movedEnough = false; isDragging = true;
    const cx = e.type==='touchstart'? e.touches[0].clientX : e.clientX;
    const cy = e.type==='touchstart'? e.touches[0].clientY : e.clientY;
    dragStartX = cx; dragStartY = cy;
    const r = widgetToggleBtn.getBoundingClientRect();
    initLeft = r.left; initTop = r.top;
    widgetToggleBtn.style.transition = 'none';
}
function doDrag(e) {
    if (!isDragging) return;
    const cx = e.type==='touchmove'? e.touches[0].clientX : e.clientX;
    const cy = e.type==='touchmove'? e.touches[0].clientY : e.clientY;
    const dx = cx - dragStartX, dy = cy - dragStartY;
    if (Math.abs(dx)>5 || Math.abs(dy)>5) {
        if (!movedEnough) chatWidget.classList.add('collapsed');
        movedEnough = true;
    }
    if (movedEnough) {
        const pad = 14;
        const nl = Math.max(pad, Math.min(window.innerWidth-78, initLeft+dx));
        const nt = Math.max(pad, Math.min(window.innerHeight-78, initTop+dy));
        widgetToggleBtn.style.bottom = 'auto';
        widgetToggleBtn.style.left   = `${nl}px`;
        widgetToggleBtn.style.top    = `${nt}px`;
    }
}
function endDrag() {
    if (!isDragging) return;
    isDragging = false;
    widgetToggleBtn.style.transition = 'transform .25s ease, box-shadow .25s ease, background .25s';
    setTimeout(()=>{ isDragging=false; },60);
}

widgetToggleBtn.addEventListener('mousedown',  startDrag);
document.addEventListener('mousemove',  doDrag);
document.addEventListener('mouseup',    endDrag);
widgetToggleBtn.addEventListener('touchstart', startDrag, {passive:true});
document.addEventListener('touchmove',  doDrag,    {passive:true});
document.addEventListener('touchend',   endDrag);

/* ================================================================
   WIDGET SMART POSITIONING & TOGGLE
   ================================================================ */
function adjustWidgetPosition() {
    const r  = widgetToggleBtn.getBoundingClientRect();
    const ww = window.innerWidth, wh = window.innerHeight;
    const cw = chatWidget.offsetWidth  || 760;
    const ch = chatWidget.offsetHeight || 595;

    // Vertical
    if (r.top < wh / 3) {
        chatWidget.style.top    = `${r.bottom + 12}px`;
        chatWidget.style.bottom = 'auto';
    } else {
        chatWidget.style.top    = 'auto';
        chatWidget.style.bottom = `${wh - r.top + 12}px`;
    }
    // Horizontal
    if (r.left > ww / 2) {
        const rp = ww - r.right;
        chatWidget.style.left  = (ww - rp < cw) ? '10px' : 'auto';
        chatWidget.style.right = (ww - rp < cw) ? 'auto' : `${rp}px`;
    } else {
        chatWidget.style.right = (r.left + cw > ww) ? '10px' : 'auto';
        chatWidget.style.left  = (r.left + cw > ww) ? 'auto' : `${r.left}px`;
    }
}

widgetToggleBtn.addEventListener('click', e => {
    if (movedEnough) { e.preventDefault(); return; }
    const closed = chatWidget.classList.contains('collapsed');
    if (closed) {
        adjustWidgetPosition();
        chatWidget.classList.remove('collapsed');
        toggleIcon.className = 'fa-solid fa-xmark';
        setTimeout(scrollToBottom, 60);
    } else {
        chatWidget.classList.add('collapsed');
        toggleIcon.className = 'fa-solid fa-scale-balanced';
    }
});
window.addEventListener('resize', ()=>{
    if (!chatWidget.classList.contains('collapsed')) adjustWidgetPosition();
});

/* ================================================================
   DRAG & DROP FILES ON CHAT BOX
   ================================================================ */
let dragCnt = 0;
chatBox.addEventListener('dragenter', e=>{e.preventDefault();e.stopPropagation();dragCnt++;chatBox.classList.add('dragover');});
chatBox.addEventListener('dragover',  e=>{e.preventDefault();e.stopPropagation();});
chatBox.addEventListener('dragleave', e=>{e.preventDefault();e.stopPropagation();if(--dragCnt===0)chatBox.classList.remove('dragover');});
chatBox.addEventListener('drop',      e=>{
    e.preventDefault();e.stopPropagation();dragCnt=0;chatBox.classList.remove('dragover');
    if(e.dataTransfer.files.length>0) setupFilePreview(e.dataTransfer.files[0]);
});

/* ================================================================
   FILE ATTACHMENT
   ================================================================ */
fileTriggerBtn.addEventListener('click', ()=> fileInput.click());
fileInput.addEventListener('change', ()=>{
    if(fileInput.files.length>0){ setupFilePreview(fileInput.files[0]); fileInput.value=''; }
});

function setupFilePreview(file) {
    clearVoice();
    currentPendingFile = file;
    previewFilename.textContent = file.name;
    previewIcon.className =
        file.type.startsWith('image/')          ? 'fa-solid fa-file-image' :
        file.type === 'application/pdf'         ? 'fa-solid fa-file-pdf'   :
        file.name.match(/\.(doc|docx)$/i)       ? 'fa-solid fa-file-word'  :
                                                  'fa-solid fa-file-lines';
    previewContainer.style.display = 'flex';
    scrollToBottom();
}

previewRemoveBtn.addEventListener('click', ()=>{
    currentPendingFile = null;
    previewContainer.style.display = 'none';
});

function resetPendingFiles() {
    currentPendingFile  = null;
    currentPendingVoice = null;
    previewContainer.style.display   = 'none';
    voiceControlsBar.style.display   = 'none';
    voiceRecordingBar.style.display  = 'none';
    if (isRecording) stopRecording();
}

/* ================================================================
   VOICE RECORDING — Web Audio API Waveform
   ================================================================ */
voiceRecordBtn.addEventListener('click', async ()=>{
    if (!isRecording) await startRecording();
});

stopRecBtn.addEventListener('click', ()=> stopRecording());

async function startRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        // AudioContext → AnalyserNode
        audioCtx     = new (window.AudioContext || window.webkitAudioContext)();
        analyserNode = audioCtx.createAnalyser();
        analyserNode.fftSize = 256;
        micSource    = audioCtx.createMediaStreamSource(stream);
        micSource.connect(analyserNode);

        // MediaRecorder
        const mimeType = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : 'audio/ogg';
        mediaRecorder = new MediaRecorder(stream, { mimeType });
        audioChunks   = [];
        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
        mediaRecorder.onstop = () => {
            pendingVoiceBlob = new Blob(audioChunks, { type: mimeType });
            voiceRecordingBar.style.display  = 'none';
            voiceControlsBar.style.display   = 'flex';
        };
        mediaRecorder.start();
        isRecording = true;

        // Show recording bar & set canvas size
        voiceRecordingBar.style.display = 'flex';
        voiceControlsBar.style.display  = 'none';
        messageInput.placeholder = 'جاري التسجيل الصوتي...';
        messageInput.disabled    = true;

        // Set canvas resolution
        requestAnimationFrame(() => {
            waveformCanvas.width  = waveformCanvas.offsetWidth  || 200;
            waveformCanvas.height = waveformCanvas.offsetHeight || 42;
            drawWaveform();
        });

        // Start timer
        recSeconds = 0;
        recTimerEl.textContent = '0:00';
        recTimerInterval = setInterval(()=>{
            recSeconds++;
            recTimerEl.textContent = formatTime(recSeconds);
        }, 1000);

    } catch(err) {
        alert('يرجى السماح بصلاحية الميكروفون لتمكين التسجيل الصوتي.');
    }
}

function stopRecording() {
    if (!isRecording) return;
    isRecording = false;

    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(t => t.stop());
    }

    cancelAnimationFrame(waveformAnimId);
    clearInterval(recTimerInterval);

    if (audioCtx) { audioCtx.close().catch(()=>{}); audioCtx = null; }
    analyserNode = null; micSource = null;

    messageInput.placeholder = 'اكتب سؤالك القانوني هنا...';
    messageInput.disabled    = false;
}

function clearVoice() {
    pendingVoiceBlob            = null;
    currentPendingVoice         = null;
    voiceControlsBar.style.display  = 'none';
    voiceRecordingBar.style.display = 'none';
    if (isRecording) stopRecording();
}

function drawWaveform() {
    if (!analyserNode) return;
    const canvas  = waveformCanvas;
    const ctx     = canvas.getContext('2d');
    const bufLen  = analyserNode.frequencyBinCount; // fftSize / 2
    const data    = new Uint8Array(bufLen);
    const BAR_CNT = 42;
    const step    = Math.max(1, Math.floor(bufLen / BAR_CNT));
    const bw      = (canvas.width / BAR_CNT) - 1;
    const cy      = canvas.height / 2;

    function draw() {
        waveformAnimId = requestAnimationFrame(draw);
        analyserNode.getByteFrequencyData(data);
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < BAR_CNT; i++) {
            const val = data[i * step] / 255;
            const bh  = Math.max(3, val * canvas.height * 0.88);
            const x   = i * (bw + 1);

            const grad = ctx.createLinearGradient(0, cy - bh/2, 0, cy + bh/2);
            grad.addColorStop(0,   'rgba(245,158,11,0.8)');
            grad.addColorStop(0.5, 'rgba(245,158,11,1)');
            grad.addColorStop(1,   'rgba(245,158,11,0.8)');
            ctx.fillStyle = grad;
            ctx.fillRect(x, cy - bh/2, bw, bh);
        }
    }
    draw();
}

/* ── Voice Control Buttons ────────────────────────────────────────── */
voiceSendBtn.addEventListener('click', ()=>{
    currentPendingVoice = pendingVoiceBlob;
    voiceControlsBar.style.display = 'none';
    performMessageSend(false);
});

voiceRetryBtn.addEventListener('click', async ()=>{
    pendingVoiceBlob    = null;
    currentPendingVoice = null;
    voiceControlsBar.style.display = 'none';
    await startRecording();
});

voiceDeleteBtn.addEventListener('click', ()=>{
    clearVoice();
});

/* ================================================================
   SIDEBAR — attach events to PHP-rendered items on page load
   ================================================================ */
document.querySelectorAll('.sidebar-session-item').forEach(item => attachSessionItemEvents(item));

/* ================================================================
   SEND MESSAGE
   ================================================================ */
async function performMessageSend(isRetry = false) {
    let messageText, fileToSend, voiceToSend;

    if (isRetry) {
        messageText = lastSentText;
        fileToSend  = lastSentFile;
        voiceToSend = lastSentVoice;
    } else {
        messageText = messageInput.value.trim();
        fileToSend  = currentPendingFile;
        voiceToSend = currentPendingVoice;

        if (!messageText && !fileToSend && !voiceToSend) return;

        lastSentText  = messageText;
        lastSentFile  = fileToSend;
        lastSentVoice = voiceToSend;

        messageInput.value  = '';
        currentPendingFile  = null;
        currentPendingVoice = null;
        previewContainer.style.display  = 'none';
        voiceControlsBar.style.display  = 'none';

        // Remove welcome card
        const wc = document.getElementById('welcome-card');
        if (wc) wc.remove();

        // ── Build user message wrapper ───────────────────────────────────
        const wrapper = document.createElement('div');
        wrapper.className = 'msg-user-wrapper msg-appear';

        const bubble = document.createElement('div');
        bubble.className = 'msg-user';

        if (fileToSend) {
            const ext = (fileToSend.name||'').split('.').pop().toLowerCase();
            const ico = ext==='pdf' ? 'fa-solid fa-file-pdf' : ['jpg','jpeg','png','gif','webp'].includes(ext) ? 'fa-solid fa-file-image' : 'fa-solid fa-file-lines';
            const fb  = document.createElement('div');
            fb.className = 'msg-file-box';
            fb.innerHTML = `<i class="${ico}"></i><div class="msg-file-info"><span class="msg-file-name">${escapeHtml(fileToSend.name)}</span></div>`;
            bubble.appendChild(fb);
        }
        if (voiceToSend) {
            const vb = document.createElement('div');
            vb.className = 'msg-file-box';
            vb.innerHTML = `<i class="fa-solid fa-file-audio"></i><div class="msg-file-info"><span class="msg-file-name">رسالة صوتية مسجلة 🎙️</span></div>`;
            bubble.appendChild(vb);
        }
        if (messageText) {
            const te = document.createElement('div');
            te.className   = 'msg-text-content';
            te.textContent = messageText;
            bubble.appendChild(te);
        }

        const ts = document.createElement('div');
        ts.className   = 'msg-timestamp';
        ts.textContent = nowTime();
        bubble.appendChild(ts);

        wrapper.appendChild(bubble);
        messagesWrapper.insertBefore(wrapper, loadingSkeleton);
        lastMsgWrapper = wrapper;

        // Push to local history
        chatHistory.push({
            role:    'user',
            content: messageText || (voiceToSend ? '[رسالة صوتية]' : '[ملف مرفق]')
        });
    }

    // Show typing indicator
    loadingSkeleton.style.display = 'flex';
    submitSendBtn.disabled = true;
    scrollToBottom();

    // Build FormData
    const fd = new FormData();
    if (sessionIdInput.value) fd.append('lawyer-chat-session-id', sessionIdInput.value);
    if (fileToSend)           fd.append('file',  fileToSend);
    if (voiceToSend)          fd.append('audio', voiceToSend, 'voice_note.webm');
    if (messageText)          fd.append('message', messageText);
    fd.append('model',   selectedModel);
    fd.append('history', JSON.stringify(chatHistory.slice(-12)));

    try {
        const response = await fetch(LAWYER_CHAT_ROUTES.send, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': LAWYER_CHAT_CSRF },
            body:    fd
        });

        if (!response.ok) {
            // Try to read a JSON error body from Laravel (e.g. 422 validation)
            let errMsg = 'HTTP ' + response.status;
            try {
                const errJson = await response.clone().json();
                if (errJson?.message) errMsg = errJson.message;
                else if (errJson?.error)   errMsg = errJson.error;
                else if (errJson?.errors) {
                    const first = Object.values(errJson.errors)[0];
                    errMsg = Array.isArray(first) ? first[0] : String(first);
                }
            } catch(_) {}
            throw new Error(errMsg);
        }

        const reader  = response.body.getReader();
        const decoder = new TextDecoder('utf-8');
        let botDiv       = null;
        let botContentEl = null;
        let botRawText   = '';
        let buffer       = '';
        let hasTokens    = false;

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop(); // hold incomplete line

            for (const line of lines) {
                const clean = line.trim();
                if (!clean.startsWith('data: ')) continue;

                const raw = clean.substring(6).trim();
                if (raw === '[DONE]') break;

                let json;
                try { json = JSON.parse(raw); } catch(pe) { continue; }

                // Handle new session created by backend
                if (json.session_id && !sessionIdInput.value) {
                    sessionIdInput.value = String(json.session_id);
                    addSessionToSidebar(json.session_id, 'محادثة جديدة');
                }

                // Stream error from server
                if (json.error) throw new Error(json.error);

                // Stream text chunk
                if (json.text) {
                    hasTokens    = true;
                    botRawText  += json.text;

                    if (!botDiv) {
                        loadingSkeleton.style.display = 'none';
                        botDiv = document.createElement('div');
                        botDiv.className = 'msg-bot msg-appear';
                        botContentEl = document.createElement('div');
                        botContentEl.className = 'msg-text-content';
                        botDiv.appendChild(botContentEl);
                        messagesWrapper.insertBefore(botDiv, loadingSkeleton);
                        botDiv._streamQueue = [];
                        botDiv._isTyping    = false;
                    }

                    // Queue chars and type them one by one at 25ms interval
                    botDiv._streamQueue.push(...json.text.split(''));
                    if (!botDiv._isTyping) {
                        botDiv._isTyping = true;
                        (function flushQueue() {
                            if (!botDiv._streamQueue.length) { botDiv._isTyping = false; return; }
                            botContentEl.appendChild(document.createTextNode(botDiv._streamQueue.shift()));
                            scrollToBottom();
                            setTimeout(flushQueue, 20);
                        })();
                    }
                }
            }
        }

        // Wait for the typing animation to finish printing every queued
        // character before finalising — this is what stops the "jump":
        // the server already finished sending data, but the on-screen
        // typing effect was still behind. Without this wait, the markdown
        // re-render below would dump all the remaining text instantly.
        if (botDiv) {
            while (botDiv._streamQueue && botDiv._streamQueue.length) {
                await new Promise(r => setTimeout(r, 30));
            }
        }

        loadingSkeleton.style.display = 'none';
        if (!hasTokens) throw new Error('EmptyStream');

        // Finalise: render markdown, add timestamp
        if (botContentEl) {
            botContentEl.innerHTML = renderMarkdown(botRawText);
            const ts = document.createElement('div');
            ts.className   = 'msg-timestamp';
            ts.textContent = nowTime();
            botDiv.appendChild(ts);
        }

        // Push bot reply to history
        chatHistory.push({ role: 'assistant', content: botRawText });

        // Show edit button on the last user message
        refreshEditButton();
        scrollToBottom();

    } catch(error) {
        loadingSkeleton.style.display = 'none';

        if (lastMsgWrapper && !lastMsgWrapper.querySelector('.msg-error-inline')) {
            const eb = document.createElement('div');
            eb.className  = 'msg-error-inline';
            eb.style.cssText = 'display:flex;align-items:center;gap:6px;color:#dc2626;font-size:10px;margin-top:4px;font-weight:500;';
            const errDetail = (error?.message && error.message !== 'EmptyStream') ? error.message : '';
            eb.innerHTML = `
                <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;font-size:12px"></i>
                <span>فشل الإرسال${errDetail ? ' — ' + errDetail : ''}</span>
                <button type="button" style="background:none;border:none;color:#b45309;text-decoration:underline;font-size:10px;font-weight:700;cursor:pointer;padding:0;margin-right:4px;font-family:inherit;"
                    onclick="this.closest('.msg-error-inline').remove();performMessageSend(true)">
                    إعادة المحاولة
                </button>`;
            lastMsgWrapper.appendChild(eb);
        }
        scrollToBottom();

    } finally {
        submitSendBtn.disabled = false;
    }
}

/* ================================================================
   INPUT EVENTS
   ================================================================ */
messageInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); performMessageSend(false); }
});
submitSendBtn.addEventListener('click', () => performMessageSend(false));

/* ================================================================
   INIT
   ================================================================ */
scrollToBottom();

// Attach edit button if messages already loaded from PHP
if (chatHistory.length > 0) refreshEditButton();

})(); // End IIFE
</script>
@endif {{-- /aiEnabled --}}