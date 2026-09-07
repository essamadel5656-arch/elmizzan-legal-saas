@extends('layouts.app')

@section('title', 'عقد بيع وتنازل عن محل تجاري | ' . $appName)

@push('styles')
<style>
    /* ===== إعدادات الطباعة (ممنوع التعديل عليها) ===== */
    @media print {
        .no-print { display: none !important; }
        body { background: white; margin: 0; padding: 0; }
        .print-area {
            display: block !important;
            font-family: 'Traditional Arabic', Arial, sans-serif;
            font-size: 16px;
            line-height: 2.2;
            direction: rtl;
            padding: 1.5cm;
        }
        .print-area h2 { text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 0.5rem; text-decoration: underline; }
        .print-area h3 { text-align: center; font-size: 18px; margin-bottom: 1.5rem; }
        .print-area p { margin-bottom: 0.5rem; }
        .signature-section { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 3rem; }
        .signature-box { text-align: center; }
        .signature-box p { margin: 0.2rem 0; font-weight: bold; }
    }

    /* ===== الحاوية والترويسة ===== */
    .create-page-container { padding: 2rem; max-width: 950px; margin: 0 auto; direction: rtl; }
    
    .dashboard-header { 
        display: flex; justify-content: space-between; align-items: flex-start; 
        margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
    .header-info p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

    /* ===== البانل والكروت ===== */
    .form-card { 
        background: #ffffff; border: 1px solid var(--border-color); 
        border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; 
        box-shadow: var(--shadow-sm); transition: all 0.3s ease;
    }
    .form-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    
    .form-card-title { 
        font-size: 1.1rem; font-weight: 800; color: var(--sidebar-bg); 
        margin-bottom: 1.5rem; padding-bottom: 0.75rem; 
        border-bottom: 2px solid var(--primary-bg); display: flex; 
        align-items: center; justify-content: space-between;
    }
    .title-with-icon { display: flex; align-items: center; gap: 0.75rem; }
    .title-with-icon i { color: var(--gold-accent); font-size: 1.2rem; }

    /* ===== تقسيم الفورم ===== */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .form-group { margin-bottom: 0.5rem; }
    .form-group.full { grid-column: 1 / -1; }
    
    .form-label { display: block; margin-bottom: 0.6rem; font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }

    .form-control { 
        width: 100%; padding: 0.9rem 1rem; border: 1px solid var(--border-color); 
        border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
        font-size: 0.95rem; transition: all 0.2s; color: var(--text-primary); box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: var(--gold-accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff; }
    .form-control::placeholder { color: var(--text-secondary); opacity: 0.7; font-size: 0.85rem; }

    /* ===== أزرار التحكم ===== */
    .form-actions { margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem; }
    
    .btn-cancel { 
        padding: 10px 20px; background-color: transparent; color: var(--text-secondary); 
        border: 1px solid var(--border-color); border-radius: 8px; font-weight: 700; 
        cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-cancel:hover { background-color: var(--primary-bg); color: var(--sidebar-bg); border-color: var(--sidebar-bg); }
    
    .btn-print-action { 
        display: flex; justify-content: center; width: 100%; padding: 16px; 
        background-color: var(--sidebar-bg); color: #ffffff; border: none; 
        border-radius: 8px; font-weight: 800; cursor: pointer; transition: 0.3s; 
        align-items: center; gap: 0.8rem; font-family: inherit; font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(27, 42, 74, 0.15);
    }
    .btn-print-action:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(184, 150, 90, 0.25); }

    .print-area { display: none; }

    @media (max-width: 768px) {
        .create-page-container { padding: 1rem; }
        .form-grid { grid-template-columns: 1fr; }
        .dashboard-header { flex-direction: column; align-items: stretch; gap: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="create-page-container">

    <!-- Header الموحد -->
    <div class="dashboard-header no-print">
        <div class="header-info">
            <h1><i class="fas fa-store" style="color: var(--gold-accent); margin-left: 8px;"></i> عقد بيع وتنازل عن محل تجاري</h1>
            <p>أدخل بيانات أطراف العقد وتفاصيل المحل التجاري ثم اضغط على زر المعاينة والطباعة أدناه.</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> العودة للمكتبة القانونية
        </a>
    </div>

    <!-- فورم الإدخال (لا تظهر في الطباعة) -->
    <div class="no-print">
        
        {{-- بيانات العقد --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-calendar-alt"></i> توقيت وتاريخ العقد</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="contract_day">اليوم</label>
                    <input type="text" id="contract_day" class="form-control" placeholder="مثال: الثلاثاء">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">تاريخ العقد</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
            </div>
        </div>

        {{-- بيانات البائع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-tag"></i> بيانات الطرف الأول (البائع)</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="seller_name">الاسم الكامل</label>
                    <input type="text" id="seller_name" class="form-control" placeholder="الاسم رباعي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_id">الرقم القومي</label>
                    <input type="text" id="seller_id" class="form-control" placeholder="14 رقم" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_religion">الديانة</label>
                    <input type="text" id="seller_religion" class="form-control" value="مسلم">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_nationality">الجنسية</label>
                    <input type="text" id="seller_nationality" class="form-control" value="مصري">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="seller_address">عنوان الإقامة</label>
                    <input type="text" id="seller_address" class="form-control" placeholder="مثال: أسوان - كيما عمارة (42) شقة (9)">
                </div>
            </div>
        </div>

        {{-- بيانات المشتري --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-check"></i> بيانات الطرف الثاني (المشتري)</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="buyer_name">الاسم الكامل</label>
                    <input type="text" id="buyer_name" class="form-control" placeholder="الاسم رباعي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_id">الرقم القومي</label>
                    <input type="text" id="buyer_id" class="form-control" placeholder="14 رقم" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_religion">الديانة</label>
                    <input type="text" id="buyer_religion" class="form-control" value="مسلم">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_nationality">الجنسية</label>
                    <input type="text" id="buyer_nationality" class="form-control" value="مصري">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">عنوان الإقامة</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="مثال: أسوان - طريق السادات - خلف مديرية التموين">
                </div>
            </div>
        </div>

        {{-- بيانات المحل التجاري --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-store"></i> بيانات المحل التجاري</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="shop_number">رقم المحل</label>
                    <input type="text" id="shop_number" class="form-control" placeholder="مثال: 22">
                </div>
                <div class="form-group">
                    <label class="form-label" for="building_number">رقم العمارة</label>
                    <input type="text" id="building_number" class="form-control" placeholder="مثال: 44">
                </div>
                <div class="form-group">
                    <label class="form-label" for="shop_area">المساحة (م²)</label>
                    <input type="text" id="shop_area" class="form-control" placeholder="المساحة بالمتر المربع">
                </div>
                <div class="form-group">
                    <label class="form-label" for="original_purchase_date">تاريخ عقد الشراء الأصلي (سند الملكية)</label>
                    <input type="date" id="original_purchase_date" class="form-control">
                </div>
            </div>
        </div>

        {{-- بيانات البيع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-handshake"></i> تفاصيل البيع والتعاقد</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="price_number">ثمن البيع (أرقام)</label>
                    <input type="text" id="price_number" class="form-control" placeholder="مثال: 1,500,000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">ثمن البيع (كتابة)</label>
                    <input type="text" id="price_text" class="form-control" placeholder="مثال: مليون وخمسمائة ألف ريالاً عمانياً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">الشرط الجزائي (أرقام)</label>
                    <input type="text" id="penalty_number" class="form-control" value="500000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_text">الشرط الجزائي (كتابة)</label>
                    <input type="text" id="penalty_text" class="form-control" value="خمسمائة ألف ريال لاغير">
                </div>
            </div>
        </div>

        {{-- زر الطباعة --}}
        <div class="form-actions">
            <button class="btn-print-action" onclick="printContract()">
                <i class="fas fa-print fa-lg"></i> معاينة وطباعة العقد الموثق
            </button>
        </div>

    </div>

    <!-- منطقة الطباعة (تظهر في الطباعة فقط) -->
    <div class="print-area" id="printArea">
        <h2>عقد بيع وتنازل عن محل تجاري</h2>
        <h3>كائن بمنطقة المحمودية (حي قدري عثمان) بأسوان</h3>

        <p>إنه في يوم <span id="p_day"></span> الموافق <span id="p_date"></span></p>
        <p>تم الاتفاق بين كل من:-</p>

        <p>
            <strong>أولاً:- السيد/</strong> <span id="p_seller_name"></span> -
            المقيم بـ <span id="p_seller_address"></span> -
            <span id="p_seller_religion"></span> - <span id="p_seller_nationality"></span> -
            بالغ سن الرشد - ويحمل بطاقة رقم قومي (<span id="p_seller_id"></span>)
            <br><strong>(طرف أول بائع)</strong>
        </p>

        <p>
            <strong>ثانياً:- السيد/</strong> <span id="p_buyer_name"></span> -
            المقيم بـ <span id="p_buyer_address"></span> -
            <span id="p_buyer_religion"></span> - <span id="p_buyer_nationality"></span> -
            بالغ سن الرشد - ويحمل بطاقة رقم قومي (<span id="p_buyer_id"></span>)
            <br><strong>(طرف ثاني مشتري)</strong>
        </p>

        <p>بعد أن أقر الطرفان بأهليتهما للتصرف والتعاقد اتفقا على ما يأتي:-</p>

        <p><strong>تمهيـــد</strong></p>
        <p>
            يمتلك الطرف الأول البائع ما هو عبارة عن محل تجاري رقم (<span id="p_shop_number"></span>)
            بالعمارة رقم (<span id="p_building_number"></span>) والكائن بمنطقة المحمودية (حي قدري عثمان) بأسوان
            والبالغ مساحته (<span id="p_shop_area"></span>) تحت العجز والزيادة، والذي آلت له ملكيته عن طريق
            الشراء من الوحدة المحلية لمركز ومدينة أسوان بموجب عقد البيع الصادر بتاريخ <span id="p_original_purchase_date"></span>،
            ولرغبة الطرف الأول في بيع المحل التجاري والتنازل عنه للطرف الثاني والقابل لذلك
            فإنهما اتفقا على ذلك بالشروط الآتية:
        </p>

        <p><strong>البند الأول:</strong> يعتبر هذا التمهيد جزءاً لا يتجزأ من هذا العقد وهو مكملاً ومفسراً له.</p>

        <p>
            <strong>البند الثاني:</strong> باع وأسقط وتنازل الطرف الأول (البائع) بكافة الضمانات القانونية والفعلية
            إلى الطرف الثاني المشتري ما هو عبارة عن محل تجاري رقم (<span id="p_shop_number2"></span>)
            بالعمارة رقم (<span id="p_building_number2"></span>) والكائن بمنطقة المحمودية (حي قدري عثمان) بأسوان
            والبالغ مساحته (<span id="p_shop_area2"></span>) تحت العجز والزيادة، والذي آلت له ملكيته عن طريق
            الشراء من الوحدة المحلية لمركز ومدينة أسوان بموجب عقد البيع الصادر بتاريخ <span id="p_original_purchase_date2"></span>.
        </p>

        <p>
            <strong>البند الثالث:-</strong> تم هذا البيع بين الطرفين نظير مبلغ إجمالي وقدره (<span id="p_price_number"></span>ج)
            (فقط <span id="p_price_text"></span>) دُفع من يد ومال الطرف الثاني المشتري إلى الطرف الأول البائع
            عند تحرير هذا العقد، ويعتبر توقيع الطرف الأول على هذا العقد بمثابة مخالصة تامة باستلامه كامل الثمن.
        </p>

        <p>
            <strong>البند الرابع:</strong> يلتزم الطرف الأول البائع بتقديم كافة مستندات الملكية الخاصة بالمحل التجاري
            محل البيع للطرف الثاني المشتري وكذلك عمل توكيل عام فيما يخص المحل التجاري بالبيع أو التنازل والإدارة،
            بالإضافة لعمل تنازل عن عدادات المياه والكهرباء والتليفون والغاز (إن وجد)، كما يتعهد الطرف الأول البائع
            بتقديم كافة المستندات المطلوبة قانوناً منه ويتعهد بالحضور لإتمام إجراءات الحكم بصحة ونفاذ أو بصحة التوقيع
            على هذا العقد عند إخطاره بذلك.
        </p>

        <p>
            <strong>البند الخامس:-</strong> يقر الطرف الثاني المشتري بأنه عاين المحل التجاري المعاينة التامة النافية
            للجهالة شرعاً وقانوناً وقبله بالحالة التي هي عليها عند التعاقد وأصبح تحت يده وحيازته من تاريخ تحرير
            هذا العقد وأصبح له حق التصرف فيه كيفما يشاء بسائر أوجه التصرفات الشرعية والقانونية.
        </p>

        <p><strong>البند السادس:-</strong> تقع مصروفات العقد وشهرته على عاتق الطرف الثاني المشتري.</p>

        <p>
            <strong>البند السابع:-</strong> يقر الطرف الأول البائع بخلو المحل التجاري من أي حقوق عينية أياً كان نوعها
            كالرهن والاختصاص والوقف والحكر وحقوق الانتفاع والارتفاق ظاهرة أو خفية، كما يقر أنه حائز لهذا المحل التجاري
            دون منازعة وبصفة ظاهرة وغير منقطعة وأنه لم يتصرف فيه من قبل سواء بالرهن أو الإيجار أو البيع
            وأن هذا التصرف بالبيع والتنازل هو الأول.
        </p>

        <p>
            <strong>البند الثامن:-</strong> بموجب هذا العقد ومن تاريخ تحريره أصبح الطرف الثاني المشتري مسئولاً مسئولية
            كاملة عن جميع المستحقات المالية المتعلقة بالمحل التجاري موضوع هذا العقد من (ضرائب ومياه أو كهرباء أو تليفون
            أو غاز إن وجد) وأن ما يسبق هذا التاريخ يتحمله الطرف الأول البائع.
        </p>

        <p>
            <strong>البند التاسع:-</strong> يلتزم الطرف الأول البائع بالحضور عند طلبه في حالة وجود أي خلافات أو نزاعات
            (تتطلب حضوره) لحلها أو قد تواجه الطرف الثاني المشتري أثناء نقل الملكية.
        </p>

        <p>
            <strong>البند العاشر:-</strong> يقر الطرف الأول البائع بصحة ملكيته للمحل التجاري موضوع العقد وعدم منازعة
            أي شخص له في ملكيته أو حيازته وبأنه ملتزم بعدم تعرض الغير للطرف الثاني المشتري أو تعرضه شخصياً.
        </p>

        <p>
            <strong>البند الحادي عشر:-</strong> إذا أخل أحد الطرفين بأي بند من بنود هذا العقد يلتزم بدفع مبلغ وقدره
            (<span id="p_penalty_number"></span>ج) (فقط <span id="p_penalty_text"></span>) كتعويض اتفاقي غير خاضع
            لرقابة القضاء في قيمته وتقديره وإنما يُحكم به بمجرد التحقق من وقوع المخالفة.
        </p>

        <p>
            <strong>البند الثاني عشر:-</strong> هذا العقد نهائي وملزم لجميع أطرافه بالمضي في بنوده وأيضاً هذا العقد
            يسري علينا وعلى ممثلينا وعلى خلفنا العام والخاص من بعدنا والله ولي التوفيق.
        </p>

        <p>
            <strong>البند الثالث عشر:-</strong> أي نزاع قد ينشأ بسبب هذا العقد يكون من اختصاص محكمة أسوان الابتدائية وجزئياتها.
        </p>

        <p><strong>البند الرابع عشر:-</strong> حرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند الاقتضاء.</p>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>الطرف الأول (البائع)</strong></p>
                <p style="text-align: right; margin-top: 10px;">الاسم/ <span id="p_seller_name2"></span></p>
                <p style="text-align: right;">رقم البطاقة/ <span id="p_seller_id2"></span></p>
                <p style="text-align: right;">التوقيع/ .........................................</p>
                <p style="text-align: right;">البصمة/ .........................................</p>
            </div>
            <div class="signature-box">
                <p><strong>الطرف الثاني (المشتري)</strong></p>
                <p style="text-align: right; margin-top: 10px;">الاسم/ <span id="p_buyer_name2"></span></p>
                <p style="text-align: right;">رقم البطاقة/ <span id="p_buyer_id2"></span></p>
                <p style="text-align: right;">التوقيع/ .........................................</p>
                <p style="text-align: right;">البصمة/ .........................................</p>
            </div>
        </div>

        <div class="signature-section" style="margin-top: 2rem;">
            <div class="signature-box">
                <p><strong>الشهـود - شاهد أول</strong></p>
                <p style="text-align: right; margin-top: 10px;">الاسم/ .........................................</p>
                <p style="text-align: right;">رقم البطاقة/ .........................................</p>
                <p style="text-align: right;">التوقيع/ .........................................</p>
            </div>
            <div class="signature-box">
                <p><strong>شاهد ثاني</strong></p>
                <p style="text-align: right; margin-top: 10px;">الاسم/ .........................................</p>
                <p style="text-align: right;">رقم البطاقة/ .........................................</p>
                <p style="text-align: right;">التوقيع/ .........................................</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function printContract() {
    const f = id => document.getElementById(id).value;
    const s = (id, val) => { const el = document.getElementById(id); if(el) el.textContent = val; };

    const fmt = dateStr => dateStr
        ? new Date(dateStr).toLocaleDateString('ar-EG')
        : '___/___/______';

    s('p_day', f('contract_day'));
    s('p_date', fmt(f('contract_date')));

    s('p_seller_name', f('seller_name'));       s('p_seller_name2', f('seller_name'));
    s('p_seller_id', f('seller_id'));           s('p_seller_id2', f('seller_id'));
    s('p_seller_religion', f('seller_religion'));
    s('p_seller_nationality', f('seller_nationality'));
    s('p_seller_address', f('seller_address'));

    s('p_buyer_name', f('buyer_name'));         s('p_buyer_name2', f('buyer_name'));
    s('p_buyer_id', f('buyer_id'));             s('p_buyer_id2', f('buyer_id'));
    s('p_buyer_religion', f('buyer_religion'));
    s('p_buyer_nationality', f('buyer_nationality'));
    s('p_buyer_address', f('buyer_address'));

    s('p_shop_number', f('shop_number'));       s('p_shop_number2', f('shop_number'));
    s('p_building_number', f('building_number')); s('p_building_number2', f('building_number'));
    s('p_shop_area', f('shop_area'));           s('p_shop_area2', f('shop_area'));
    s('p_original_purchase_date', fmt(f('original_purchase_date')));
    s('p_original_purchase_date2', fmt(f('original_purchase_date')));

    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_penalty_text', f('penalty_text'));

    window.print();
}
</script>
@endpush

@endsection