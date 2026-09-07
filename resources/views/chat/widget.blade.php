<div class="ai-widget-container" style="position: fixed; bottom: 1.5rem; left: 1.5rem; z-index: 9999;">
    <button onclick="showPremiumTeaser()" class="ai-widget-btn" style="
        background: linear-gradient(135deg, #d4af37 0%, #b8962e 100%);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
    " onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 15px 30px rgba(212, 175, 55, 0.6)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px rgba(212, 175, 55, 0.4)';">
        <i class="fas fa-robot" style="font-size: 1.8rem;"></i>
    </button>
</div>

<!-- Premium Teaser Modal -->
<div id="premiumTeaserModal" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
">
    <div style="
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        transform: scale(0.9);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    " id="premiumTeaserContent">
        <button onclick="hidePremiumTeaser()" style="
            position: absolute;
            top: 1rem; right: 1rem;
            background: none; border: none;
            color: var(--text-secondary, #64748b);
            font-size: 1.2rem;
            cursor: pointer;
        "><i class="fas fa-times"></i></button>
        
        <div style="
            width: 80px; height: 80px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            color: #d4af37;
            font-size: 2.5rem;
        ">
            <i class="fas fa-robot"></i>
        </div>
        
        <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--text-primary, #0f172a); margin-bottom: 0.75rem;">المساعد القانوني الذكي</h3>
        <p style="font-size: 0.95rem; color: var(--text-secondary, #475569); line-height: 1.6; margin-bottom: 1.5rem;">
            هذه الميزة حصرية لاشتراكات الباقة المتقدمة (Pro). قم بترقية حسابك للوصول إلى التحليل الذكي للقضايا وصياغة المذكرات الآلية.
        </p>
        
        <button onclick="hidePremiumTeaser()" style="
            background: linear-gradient(135deg, #d4af37 0%, #b8962e 100%);
            color: #fff; border: none; border-radius: 8px;
            padding: 0.75rem 2rem; font-weight: 700; font-family: inherit;
            cursor: pointer; width: 100%; transition: opacity 0.2s;
        " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            فهمت ذلك
        </button>
    </div>
</div>

<script>
    function showPremiumTeaser() {
        const modal = document.getElementById('premiumTeaserModal');
        const content = document.getElementById('premiumTeaserContent');
        if(modal && content) {
            modal.style.display = 'flex';
            setTimeout(() => {
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);
        }
    }

    function hidePremiumTeaser() {
        const modal = document.getElementById('premiumTeaserModal');
        const content = document.getElementById('premiumTeaserContent');
        if(modal && content) {
            content.style.transform = 'scale(0.9)';
            content.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }
</script>
