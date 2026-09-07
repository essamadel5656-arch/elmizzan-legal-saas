@extends('layouts.app')

@section('title', 'عقد بيع نهائي لقطعة أرض | ' . $appName)

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
        .print-area h2 { text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 1.5rem; text-decoration: underline; }
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
            <h1><i class="fas fa-map-marked-alt" style="color: var(--gold-accent); margin-left: 8px;"></i> عقد بيع نهائي لقطعة أرض</h1>
            <p>أدخل بيانات أطراف العقد وتفاصيل قطعة الأرض وحدودها، ثم اضغط على زر المعاينة والطباعة أدناه.</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> العودة للمكتبة القانونية
        </a>
    </div>

    <!-- فورم الإدخال (لا تظهر في الطباعة) -->
    <div class="no-print">
        
        {{-- بيانات البائع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-tag"></i> بيانات الطرف الأول (البائع)</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="seller_name">اسم البائع</label>
                    <input type="text" id="seller_name" class="form-control" placeholder="الاسم رباعي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_id">الرقم القومي للبائع</label>
                    <input type="text" id="seller_id" class="form-control" placeholder="14 رقم" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_religion">ديانة البائع</label>
                    <input type="text" id="seller_religion" class="form-control" value="مسلم">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_nationality">جنسية البائع</label>
                    <input type="text" id="seller_nationality" class="form-control" value="مصري">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="seller_address">عنوان البائع</label>
                    <input type="text" id="seller_address" class="form-control" placeholder="محل الإقامة بالتفصيل المذكور في البطاقة">
                </div>
            </div>
        </div>

        {{-- بيانات المشتري --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-check"></i> بيانات الطرف الثاني (المشتري)</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="buyer_name">اسم المشتري</label>
                    <input type="text" id="buyer_name" class="form-control" placeholder="الاسم رباعي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_id">الرقم القومي للمشتري</label>
                    <input type="text" id="buyer_id" class="form-control" placeholder="14 رقم" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_religion">ديانة المشتري</label>
                    <input type="text" id="buyer_religion" class="form-control" value="مسلم">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_nationality">جنسية المشتري</label>
                    <input type="text" id="buyer_nationality" class="form-control" value="مصري">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">عنوان المشتري</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="محل الإقامة بالتفصيل المذكور في البطاقة">
                </div>
            </div>
        </div>

        {{-- بيانات قطعة الأرض --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-draw-polygon"></i> بيانات قطعة الأرض وحدودها</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="property_location">موقع قطعة الأرض</label>
                    <input type="text" id="property_location" class="form-control" placeholder="مثال: أسوان - حي ...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="property_street">الشارع / المنطقة</label>
                    <input type="text" id="property_street" class="form-control" placeholder="مثال: شارع المحطة">
                </div>
                <div class="form-group">
                    <label class="form-label" for="property_area">المساحة</label>
                    <input type="text" id="property_area" class="form-control" placeholder="مثال: 500 متر مربع">
                </div>
                
                {{-- الحدود --}}
                <div class="form-group">
                    <label class="form-label" for="border_north">الحد البحري (شمالاً)</label>
                    <input type="text" id="border_north" class="form-control" placeholder="يحدها من الشمال...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="border_south">الحد القبلي (جنوباً)</label>
                    <input type="text" id="border_south" class="form-control" placeholder="يحدها من الجنوب...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="border_east">الحد الشرقي</label>
                    <input type="text" id="border_east" class="form-control" placeholder="يحدها من الشرق...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="border_west">الحد الغربي</label>
                    <input type="text" id="border_west" class="form-control" placeholder="يحدها من الغرب...">
                </div>

                <div class="form-group full">
                    <label class="form-label" for="property_origin">كيف آلت الملكية للبائع</label>
                    <input type="text" id="property_origin" class="form-control" placeholder="مثال: عن طريق الشراء بموجب العقد المؤرخ في ...">
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
                    <input type="text" id="price_number" class="form-control" placeholder="مثال: 2,000,000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">ثمن البيع (كتابة)</label>
                    <input type="text" id="price_text" class="form-control" placeholder="مثال: اثنان مليون ريالاً عمانياً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">الشرط الجزائي (أرقام)</label>
                    <input type="text" id="penalty_number" class="form-control" value="150000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">تاريخ العقد</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="court_name">المحكمة المختصة بالنزاعات</label>
                    <input type="text" id="court_name" class="form-control" value="محكمة أسوان الابتدائية">
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
        <h2>عقد بيع نهائي لقطعة أرض</h2>

        <p>إنه في يوم الموافق <span id="p_date"></span> بين كلٍ من:</p>

        <p><strong>أولاً: السيد/</strong> <span id="p_seller_name"></span> - <span id="p_seller_nationality"></span> - <span id="p_seller_religion"></span> - بالغ سن الرشد - المقيم بـ <span id="p_seller_address"></span> - ويحمل بطاقة رقم قومي (<span id="p_seller_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف أول بائع)</p>

        <p><strong>ثانياً: السيد/</strong> <span id="p_buyer_name"></span> - <span id="p_buyer_nationality"></span> - <span id="p_buyer_religion"></span> - بالغ سن الرشد - المقيم بـ <span id="p_buyer_address"></span> - ويحمل بطاقة رقم قومي (<span id="p_buyer_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف ثاني مشتري)</p>

        <p>بعد أن أقر الطرفان بأهليتهما للتصرف والتعاقد والتراضي اتفقا فيما بينهما على ما يلي:</p>

        <p><strong>البند التمهيدي:</strong> يمتلك الطرف الأول (البائع) قطعة أرض مساحة كائنة بـ <span id="p_property_location"></span> - شارع <span id="p_property_street"></span> تبلغ مساحتها (<span id="p_property_area"></span>) تحت العجز والزيادة، وحدودها كالتالي: الحد البحري: <span id="p_border_north"></span>، الحد القبلي: <span id="p_border_south"></span>، الحد الشرقي: <span id="p_border_east"></span>، الحد الغربي: <span id="p_border_west"></span>. وقد آلت الملكية إليه <span id="p_property_origin"></span>.</p>

        <p><strong>البند الأول:</strong> يعتبر التمهيد السابق جزءاً لا يتجزأ من هذا العقد مكملاً ومفسراً لبنوده.</p>
        
        <p><strong>البند الثاني:</strong> باع وأسقط وتنازل بكافة الضمانات القانونية والفعلية الطرف الأول إلى الطرف الثاني قطعة الأرض الكائنة بـ <span id="p_property_location2"></span> - شارع <span id="p_property_street2"></span> تبلغ مساحتها (<span id="p_property_area2"></span>) تحت العجز والزيادة.</p>
        
        <p><strong>البند الثالث:</strong> يقر الطرف الثاني (المشتري) بأنه عاين قطعة الأرض المعاينة التامة النافية للجهالة وقبل شراءها بحالتها التي هي عليها وقت التعاقد.</p>
        
        <p><strong>البند الرابع:</strong> تم هذا البيع نظير ثمن إجمالي وقدره (<span id="p_price_number"></span>) (فقط <span id="p_price_text"></span> لاغير) دفعها الطرف الثاني عداً ونقداً بمجلس هذا العقد ويعتبر توقيعه على هذا العقد بمثابة مخالصة نهائية بقبض كامل الثمن.</p>
        
        <p><strong>البند الخامس:</strong> يقر الطرف الأول (البائع) بأن الأرض المبيعة موضوع عقد البيع في حيازته وأن هذه الحيازة هادئة ومستقرة وأنه هو الحائز الوحيد للأرض المبيعة.</p>
        
        <p><strong>البند السادس:</strong> بموجب هذا العقد وبمجرد التوقيع عليه يلتزم الطرف الأول بتسليم الطرف الثاني الأرض موضوع البيع، ويحق لهذا الأخير وضع يده على هذه الأرض دون أدنى معارضة من الطرف الأول، وفي حالة وجود أي حائز آخر يكون الطرف الأول ملزماً برد ثمن البيع مضافاً إليه مبلغ (<span id="p_penalty_number"></span>) كشرط جزائي.</p>
        
        <p><strong>البند السابع:</strong> يلتزم الطرف الأول بالحضور مع الطرف الثاني أمام المحكمة المختصة للإقرار بصحة توقيعه على هذا العقد أو إعطاء توكيل له في ذلك الشأن، كما يلتزم بالحضور أمام جميع الجهات الحكومية أو غير الحكومية لاتخاذ إجراءات نقل الملكية للطرف الثاني متى طلب منه ذلك.</p>
        
        <p><strong>البند الثامن:</strong> يقر الطرف الأول (البائع) بخلو الأرض المبيعة موضوع هذا العقد من كافة الرهون أو الديون أو الحقوق العينية للغير.</p>
        
        <p><strong>البند التاسع:</strong> يلتزم الطرفان في حالة الفسخ أو الإخلال بأي بند من بنود هذا العقد بدفع مبلغ وقدره (<span id="p_penalty_breach"></span>) للطرف الآخر كشرط جزائي في العقد.</p>
        
        <p><strong>البند العاشر:</strong> تختص <span id="p_court_name"></span> على اختلاف درجاتها دون غيرها بكافة النزاعات التي تنشأ عن تفسير أو توثيق هذا العقد.</p>
        
        <p><strong>البند الحادي عشر:</strong> حرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم.</p>

        <div class="signature-section">
            <div class="signature-box">
                <p>الطرف الأول (البائع)</p>
                <p>الاسم/ <span id="p_seller_name2"></span></p>
                <p>الرقم القومي/ <span id="p_seller_id2"></span></p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
                <p>البصمة/ .........................................</p>
            </div>
            <div class="signature-box">
                <p>الطرف الثاني (المشتري)</p>
                <p>الاسم/ <span id="p_buyer_name2"></span></p>
                <p>الرقم القومي/ <span id="p_buyer_id2"></span></p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
                <p>البصمة/ .........................................</p>
            </div>
        </div>

        <div class="signature-section" style="margin-top: 3rem;">
            <div class="signature-box">
                <p>شاهد أول</p>
                <p>الاسم/ .........................................</p>
                <p>الرقم القومي/ .........................................</p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
            </div>
            <div class="signature-box">
                <p>شاهد ثاني</p>
                <p>الاسم/ .........................................</p>
                <p>الرقم القومي/ .........................................</p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function printContract() {
    const f = id => document.getElementById(id).value;
    const s = (id, val) => { const el = document.getElementById(id); if(el) el.textContent = val; };

    const date = f('contract_date') ? new Date(f('contract_date')).toLocaleDateString('ar-EG') : '___/___/______';

    s('p_date', date);
    s('p_seller_name', f('seller_name')); s('p_seller_name2', f('seller_name'));
    s('p_seller_id', f('seller_id')); s('p_seller_id2', f('seller_id'));
    s('p_seller_address', f('seller_address'));
    s('p_seller_religion', f('seller_religion'));
    s('p_seller_nationality', f('seller_nationality'));
    s('p_buyer_name', f('buyer_name')); s('p_buyer_name2', f('buyer_name'));
    s('p_buyer_id', f('buyer_id')); s('p_buyer_id2', f('buyer_id'));
    s('p_buyer_address', f('buyer_address'));
    s('p_buyer_religion', f('buyer_religion'));
    s('p_buyer_nationality', f('buyer_nationality'));
    s('p_property_location', f('property_location')); s('p_property_location2', f('property_location'));
    s('p_property_street', f('property_street')); s('p_property_street2', f('property_street'));
    s('p_property_area', f('property_area')); s('p_property_area2', f('property_area'));
    s('p_border_north', f('border_north'));
    s('p_border_south', f('border_south'));
    s('p_border_east', f('border_east'));
    s('p_border_west', f('border_west'));
    s('p_property_origin', f('property_origin'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_penalty_breach', f('penalty_number'));
    s('p_court_name', f('court_name'));

    window.print();
}
</script>
@endpush

@endsection