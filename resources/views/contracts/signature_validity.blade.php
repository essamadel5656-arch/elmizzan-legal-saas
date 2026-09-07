@extends('layouts.app')

@section('title', 'صحة توقيع | ' . $appName)

@push('styles')
<style>
@media print {
    .no-print { display: none !important; }
    body { background: white; }

    .form-container { max-width: none !important; margin: 0 !important; padding: 0 !important; }

    /* إخفاء الشات بوت وأي عناصر خارجية */
    iframe, [id*="chat"], [class*="chat"], [id*="bot"], [class*="bot"],
    [id*="widget"], [class*="widget"], [id*="crisp"], [class*="crisp"],
    [id*="intercom"], [class*="intercom"], [id*="zendesk"], [class*="zendesk"],
    div[style*="position: fixed"], div[style*="position:fixed"] {
        display: none !important;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
    }

    .print-area {
        display: block !important;
        font-family: 'Traditional Arabic', Arial, sans-serif;
        font-size: 13px;
        line-height: 1.8;
        direction: rtl;
        padding: 1cm 1.5cm;
        color: black;
        margin: 0 !important;
    }

    .print-header {
        text-align: center;
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid black;
    }

    .print-header h2 { font-size: 16px; font-weight: bold; margin: 0; }
    .print-header h3 { font-size: 14px; font-weight: bold; margin: 0; }
    .print-header p { font-size: 12px; margin: 0; }

    .print-layout {
        display: grid;
        grid-template-columns: 1fr 180px;
        gap: 1rem;
    }

    .print-main p { margin-bottom: 0.3rem; text-align: justify; }

    .print-sidebar {
        border: 1px solid black;
        padding: 0.5rem;
        font-size: 11px;
        height: fit-content;
    }

    .print-sidebar p { margin-bottom: 0.4rem; }

    .signature-area {
        margin-top: 2rem;
        text-align: center;
    }
}

.form-container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
.form-header { text-align: center; margin-bottom: 2rem; }
.form-header h1 { font-size: 1.8rem; font-weight: 700; color: var(--text-primary); }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
.form-group { display: flex; flex-direction: column; gap: 0.4rem; }
.form-group.full { grid-column: 1 / -1; }
.form-group label { font-size: 0.9rem; font-weight: 600; color: var(--text-primary); }
.form-group input, .form-group textarea { padding: 0.6rem 0.8rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--secondary-bg); color: var(--text-primary); font-size: 0.95rem; }
.form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--accent-color); }
.btn-print { display: block; width: 100%; padding: 0.9rem; background: var(--accent-color, #1e293b); color: white !important; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 1.5rem; transition: all 0.2s ease; }
.btn-print:hover { background: #0f172a; color: white !important; }
.back-link { display: inline-block; margin-bottom: 1rem; color: var(--accent-color); text-decoration: none; font-weight: 600; }
.section-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; margin: 1.5rem 0 1rem; grid-column: 1 / -1; }
.print-area { display: none; }
</style>
@endpush

@section('content')
<div class="form-container" dir="rtl">

    <a href="{{ route('contracts.index') }}" class="back-link no-print">← العودة للمكتبة القانونية</a>

    <div class="form-header no-print">
        <h1>صحة توقيع</h1>
        <p style="color: var(--text-secondary);">أدخل البيانات ثم اضغط طباعة</p>
    </div>

    <div class="no-print">
        <div class="form-grid">
            <div class="section-title">بيانات المحكمة</div>
            <div class="form-group">
                <label>اسم المحكمة</label>
                <input type="text" id="court_name" value="محكمة أسوان الجزئية">
            </div>
            <div class="form-group">
                <label>الدائرة</label>
                <input type="text" id="court_division" value="الرابعة">
            </div>
            <div class="form-group">
                <label>تاريخ الإعلان</label>
                <input type="date" id="notice_date">
            </div>

            <div class="section-title">بيانات الطالب (المدعي)</div>
            <div class="form-group">
                <label>اسم الطالب</label>
                <input type="text" id="plaintiff_name">
            </div>
            <div class="form-group">
                <label>الرقم القومي للطالب</label>
                <input type="text" id="plaintiff_id">
            </div>
            <div class="form-group full">
                <label>عنوان الطالب</label>
                <input type="text" id="plaintiff_address">
            </div>

            <div class="section-title">بيانات المُعلن إليه (المدعى عليه)</div>
            <div class="form-group">
                <label>اسم المُعلن إليه</label>
                <input type="text" id="defendant_name">
            </div>
            <div class="form-group">
                <label>الرقم القومي</label>
                <input type="text" id="defendant_id">
            </div>
            <div class="form-group full">
                <label>عنوان المُعلن إليه</label>
                <input type="text" id="defendant_address">
            </div>
            <div class="form-group full">
                <label>مخاطباً مع</label>
                <input type="text" id="contact_with">
            </div>

            <div class="section-title">بيانات عقد البيع</div>
            <div class="form-group">
                <label>تاريخ عقد البيع</label>
                <input type="date" id="contract_date">
            </div>
            <div class="form-group full">
                <label>وصف المبيع</label>
                <textarea id="property_desc" rows="2" placeholder="مثال: شقة سكنية كائنة في..."></textarea>
            </div>
            <div class="form-group full">
                <label>حدود المبيع (اختياري)</label>
                <textarea id="property_borders" rows="2" placeholder="الحد الشرقي: ... الحد الغربي: ..."></textarea>
            </div>
            <div class="form-group">
                <label>ثمن البيع (أرقام)</label>
                <input type="text" id="price_number">
            </div>
            <div class="form-group">
                <label>ثمن البيع (كتابة)</label>
                <input type="text" id="price_text">
            </div>

            <div class="section-title">بيانات الدعوى والجلسات</div>
            <div class="form-group">
                <label>رقم الدعوى</label>
                <input type="text" id="case_number">
            </div>
            <div class="form-group">
                <label>سنة الدعوى</label>
                <input type="text" id="case_year">
            </div>
            <div class="form-group">
                <label>تاريخ الجلسة الحالية</label>
                <input type="date" id="current_session">
            </div>
            <div class="form-group">
                <label>تاريخ الجلسة القادمة</label>
                <input type="date" id="next_session">
            </div>
            <div class="form-group">
                <label>يوم الجلسة القادمة</label>
                <input type="text" id="next_session_day" placeholder="مثال: الأحد">
            </div>
            <div class="form-group">
                <label>اسم المحامي (وكيل المدعي) *</label>
                <input type="text" id="lawyer_name" placeholder="اكتب اسم المحامي" required>
            </div>
        </div>

        <button type="button" class="btn-print" onclick="printContract()">🖨️ معاينة وطباعة</button>
    </div>

    <div class="print-area" id="printArea">
        <!-- هيدر المكتب -->
        <div class="print-header">
            <h2>⚖️ {{ $appName }}</h2>
            <p>أسوان - جمهورية مصر العربية</p>
        </div>

        <div class="print-layout">
            <!-- المحتوى الرئيسي -->
            <div class="print-main">
                <p>إنه في يوم الموافق <span id="p_notice_date"></span></p>

                <p>بناءً على طلب السيد/ <span id="p_plaintiff_name"></span> – رقم قومي <span id="p_plaintiff_id"></span> – المقيم في <span id="p_plaintiff_address"></span></p>
                <p>ومحله المختار مكتب الأستاذ/ <span id="p_lawyer_name"></span> – المحامي بأسوان.</p>

                <p>أنا/ محضر <span id="p_court_name"></span> قد انتقلت في تاريخه وأعلنت:</p>

                <p>السيد/ <span id="p_defendant_name"></span> – رقم قومي <span id="p_defendant_id"></span> - المقيم في <span id="p_defendant_address"></span></p>
                <p>مخاطباً مع/ <span id="p_contact_with"></span></p>

                <p><strong>وأعلنته بالآتي:</strong></p>

                <p>أقامت الطالبة الدعوى رقم <span id="p_case_number"></span> لسنة <span id="p_case_year"></span> صحة توقيع <span id="p_court_name2"></span> مدني جزئي الدائرة <span id="p_court_division"></span> ضد المعلن إليه بالطلبات الواردة بأصل الصحيفة على سند من القول:</p>

                <p>"بموجب عقد بيع مؤرخ <span id="p_contract_date"></span> باع وأسقط وتنازل بكافة الضمانات الفعلية والقانونية المُعلن إليه للطالبة ما هو عبارة عن <span id="p_property_desc"></span></p>

                <p id="p_borders_block"><span id="p_property_borders"></span></p>

                <p>كما تم هذا البيع والإسقاط والتنازل بين طرفي هذا العقد مقابل ثمن إجمالي ومقداره <span id="p_price_number"></span> ريال (فقط <span id="p_price_text"></span> لاغير) عداً ونقداً بمجلس العقد.</p>

                <p>وحيث نصت المادة (54) من قانون الإثبات على أنه: "يجوز لمن بيده محرر غير رسمي أن يختصم من يشهد عليه ذلك المحرر ليقر بأنه بخطه أو بإمضائه أو بختمه أو ببصمة أصبعه ..." مما يحق للطالبة إقامة هذه الدعوى بطلب الحكم لها بصحة توقيع المُعلن إليه على عقد البيع المؤرخ في <span id="p_contract_date2"></span>"</p>

                <p>حيث أنه كان محدداً لنظر هذه الدعوى جلسة يوم الموافق <span id="p_current_session"></span>، وبهذه الجلسة قررت عدالة المحكمة تأجيل نظر الدعوى لجلسة يوم الموافق <span id="p_next_session"></span> لإعلان المُعلن إليه بأصل الصحيفة.</p>

                <p>ولما كانت الحالة هذه وكان الطالب يهمه تنفيذ قرار المحكمة.</p>

                <p><strong>بناءً عليه</strong></p>

                <p>أنا المحضر سالف الذكر قد انتقلت وأعلنت المُعلن إليه بصورة من عريضة الدعوى هذه وكلفته بالحضور أمام <span id="p_court_name3"></span> الدائرة <span id="p_court_division2"></span> صحة توقيع بالجلسة التي ستنعقد بها علناً بسراي المحكمة – بمجمع محاكم أسوان – في يوم <span id="p_next_session_day"></span> الموافق <span id="p_next_session2"></span> من الساعة التاسعة صباحاً للمرافعة وسماعه الحكم:</p>

                <p>بـ"صحة توقيعه" على عقد البيع المؤرخ في <span id="p_contract_date3"></span> مع إلزامه بالمصروفات ومقابل أتعاب المحاماة، مع حفظ كافة حقوق المدعية الأخرى أياً كان نوعها.</p>

                <p><strong>ولأجل العلم/</strong></p>

                <div class="signature-area">
                    <p>وكيل المدعي</p>
                    <p>المحامي/ <span id="p_lawyer_name2"></span></p>
                </div>
            </div>

            <!-- الشريط الجانبي -->
            <div class="print-sidebar">
                <p><strong>الموضوع</strong></p>
                <p>إعلان بأصل صحيفة الدعوى رقم (<span id="p_case_number2"></span>) لسنة <span id="p_case_year2"></span></p>
                <p>مقدمة من المدعية وتحت مسئوليتها.</p>
                <br>
                <p><strong>وكيل المدعي</strong></p>
                <p>المحامي</p>
                <p>⚖️ {{ $appName }}</p>
                <p><span id="p_lawyer_name3"></span></p>
                <p>المحامي بأسوان</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function printContract() {
    // f و s آمنتين: لو العنصر مش موجود في الصفحة، الكود يكمل عادي من غير ما يقف بـ Error
    const f = id => {
        const el = document.getElementById(id);
        return el ? el.value : '';
    };
    const s = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    };
    const formatDate = id => {
        const val = f(id);
        if (!val) return '___/___/______';
        return new Date(val).toLocaleDateString('ar-EG');
    };

    // التأكد من إدخال اسم المحامي قبل الطباعة
    if (!f('lawyer_name').trim()) {
        alert('من فضلك أدخل اسم المحامي (وكيل المدعي) قبل الطباعة.');
        document.getElementById('lawyer_name').focus();
        return;
    }

    s('p_lawyer_name', f('lawyer_name'));
    s('p_lawyer_name2', f('lawyer_name'));
    s('p_lawyer_name3', f('lawyer_name'));
    s('p_court_name', f('court_name'));
    s('p_court_name2', f('court_name'));
    s('p_court_name3', f('court_name'));
    s('p_court_division', f('court_division'));
    s('p_court_division2', f('court_division'));
    s('p_notice_date', formatDate('notice_date'));
    s('p_plaintiff_name', f('plaintiff_name'));
    s('p_plaintiff_id', f('plaintiff_id'));
    s('p_plaintiff_address', f('plaintiff_address'));
    s('p_defendant_name', f('defendant_name'));
    s('p_defendant_id', f('defendant_id'));
    s('p_defendant_address', f('defendant_address'));
    s('p_contact_with', f('contact_with'));
    s('p_contract_date', formatDate('contract_date'));
    s('p_contract_date2', formatDate('contract_date'));
    s('p_contract_date3', formatDate('contract_date'));
    s('p_property_desc', f('property_desc'));
    s('p_property_borders', f('property_borders'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_case_number', f('case_number'));
    s('p_case_number2', f('case_number'));
    s('p_case_year', f('case_year'));
    s('p_case_year2', f('case_year'));
    s('p_current_session', formatDate('current_session'));
    s('p_next_session', formatDate('next_session'));
    s('p_next_session2', formatDate('next_session'));
    s('p_next_session_day', f('next_session_day'));

    // إخفاء بلوك الحدود لو فارغ
    const bordersBlock = document.getElementById('p_borders_block');
    if (bordersBlock) {
        bordersBlock.style.display = f('property_borders') ? '' : 'none';
    }

    // طباعة بعد التأكد من اكتمال تحديث المحتوى
    setTimeout(() => window.print(), 50);
}
</script>
@endpush

@endsection