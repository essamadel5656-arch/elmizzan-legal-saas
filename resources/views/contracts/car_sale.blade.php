@extends('layouts.app')

@section('title', 'عقد بيع سيارة مع التزام بنقل الملكية | ' . $appName)

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
            <h1><i class="fas fa-car" style="color: var(--gold-accent); margin-left: 8px;"></i> عقد بيع سيارة مع التزام بنقل الملكية</h1>
            <p>أدخل بيانات أطراف العقد وتفاصيل السيارة ثم اضغط على زر المعاينة والطباعة أدناه.</p>
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
                    <input type="text" id="seller_id" class="form-control" placeholder="14 رقم">
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
                    <input type="text" id="buyer_id" class="form-control" placeholder="14 رقم">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">عنوان المشتري</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="محل الإقامة بالتفصيل المذكور في البطاقة">
                </div>
            </div>
        </div>

        {{-- بيانات السيارة --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-car-side"></i> بيانات السيارة</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="car_type">نوع السيارة</label>
                    <input type="text" id="car_type" class="form-control" placeholder="مثال: كيا سبورتاج هاتشباك">
                </div>
                <div class="form-group">
                    <label class="form-label" for="car_model">موديل السيارة</label>
                    <input type="text" id="car_model" class="form-control" placeholder="مثال: 2019">
                </div>
                <div class="form-group">
                    <label class="form-label" for="car_color">اللون</label>
                    <input type="text" id="car_color" class="form-control" placeholder="مثال: فضي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="car_chassis">رقم الشاسيه</label>
                    <input type="text" id="car_chassis" class="form-control" placeholder="رقم الشاسيه كاملاً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="car_engine">رقم الموتور</label>
                    <input type="text" id="car_engine" class="form-control" placeholder="رقم الموتور">
                </div>
                <div class="form-group">
                    <label class="form-label" for="car_plate">رقم اللوحات</label>
                    <input type="text" id="car_plate" class="form-control" placeholder="حروف وأرقام اللوحة">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="car_license_date">تاريخ انتهاء رخصة القيادة / الرهن</label>
                    <input type="text" id="car_license_date" class="form-control" placeholder="مثال: 2025/12/31">
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
                    <input type="text" id="price_number" class="form-control" placeholder="مثال: 850,000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">ثمن البيع (كتابة)</label>
                    <input type="text" id="price_text" class="form-control" placeholder="مثال: ثمانمائة وخمسون ألف ريالاً عمانياً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">الشرط الجزائي (أرقام)</label>
                    <input type="text" id="penalty_number" class="form-control" value="250000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">تاريخ العقد</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="court_name">المحكمة المختصة بالنزاعات</label>
                    <input type="text" id="court_name" class="form-control" value="محاكم أسوان الابتدائية">
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
        <h2>عقد بيع سيارة مع التزام بنقل الملكية</h2>

        <p>إنه في يوم الموافق <span id="p_date"></span> تم الاتفاق بين كلٍ من:</p>

        <p><strong>أولاً: السيد/ة </strong> <span id="p_seller_name"></span> - مصري الجنسية - مسلم الديانة - بالغ سن الرشد - المقيم بـ <span id="p_seller_address"></span>، ويحمل بطاقة رقم قومي (<span id="p_seller_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف أول بائع)</p>

        <p><strong>ثانياً: السيد/ة </strong> <span id="p_buyer_name"></span> - مصري الجنسية - مسلم الديانة - بالغ سن الرشد - المقيم بـ <span id="p_buyer_address"></span>، ويحمل بطاقة رقم قومي (<span id="p_buyer_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف ثاني مشتري)</p>

        <p><strong>تمهيد:</strong> يمتلك الطرف الأول (البائع) سيارة نوع (<span id="p_car_type"></span>) موديل <span id="p_car_model"></span>، اللون: <span id="p_car_color"></span>، رقم الشاسيه (<span id="p_car_chassis"></span>)، موتور رقم (<span id="p_car_engine"></span>)، رقم لوحات (<span id="p_car_plate"></span>)، ولرغبة الطرف الأول (البائع) في بيع هذه السيارة إلى الطرف الثاني (المشتري) القابل لذلك.</p>

        <p>وبعد أن أقر الطرفان بأهليتهما للتصرف والتعاقد اتفقا على ما يلي:</p>

        <p><strong>البند الأول:</strong> يعتبر التمهيد السابق جزءاً لا يتجزأ من هذا العقد ومكملاً ومفسراً له.</p>
        <p><strong>البند الثاني - موضوع العقد:</strong> باع وأسقط وتنازل بكافة الضمانات القانونية والفعلية الطرف الأول إلى الطرف الثاني القابل لذلك، السيارة الآتي بياناتها: النوع: <span id="p_car_type2"></span>، الموديل: <span id="p_car_model2"></span>، اللون: <span id="p_car_color2"></span>، رقم الشاسيه: <span id="p_car_chassis2"></span>، رقم الموتور: <span id="p_car_engine2"></span>، رقم اللوحات: <span id="p_car_plate2"></span>.</p>
        <p><strong>البند الثالث - الثمن:</strong> تم هذا البيع بثمن مبلغ وقدره (<span id="p_price_number"></span>) (فقط <span id="p_price_text"></span> لاغير) ويقر الطرف الأول (البائع) بأنه استلم كامل الثمن من الطرف الثاني بمجلس هذا العقد ويعتبر توقيعه على هذا العقد بمثابة مخالصة تامة له.</p>
        <p><strong>البند الرابع - نقل الحيازة والانتفاع:</strong> أقر الطرف الأول بأنه سلّم في تاريخ التوقيع السيارة تسليماً فعلياً للطرف الثاني (المشتري)، وانتقلت الحيازة الكاملة وله وحده حق الانتفاع بها.</p>
        <p><strong>البند الخامس - حظر البيع والأقساط:</strong> يقر الطرفان بأن السيارة مرتبطة بعقد عليها رهن حتى <span id="p_car_license_date"></span>. وقد اتفق الطرفان على ما يلي: 1. يلتزم الطرف الأول البائع بسداد الأقساط في مواعيدها. 2. في حال التأخير في السداد يتحمل الطرف الملتزم كامل المسؤولية القانونية والمالية. 3. يتعهد الطرف الأول باتخاذ كافة الإجراءات اللازمة لرفع حظر البيع فور انتهاء مدته.</p>
        <p><strong>البند السادس - الالتزام النهائي بنقل الملكية:</strong> يتعهد الطرف الأول تعهداً صريحاً وغير قابل للرجوع فيه بأنه فور انتهاء رهن البيع وسداد كامل الأقساط: 1. الحضور شخصياً أمام المرور المختص أو الشهر العقاري. 2. التوقيع على عقد البيع النهائي أو إصدار توكيل رسمي بالبيع لصالح ولنفس الطرف الثاني. 3. نقل الملكية فوراً دون تأخير أو مماطلة. وفي حال الامتناع يكون للطرف الثاني الحق في إقامة دعوى صحة ونفاذ أو اتخاذ أي إجراء قانوني لنقل الملكية ويعد هذا العقد سنداً كاملاً في الإثبات.</p>
        <p><strong>البند السابع - حظر التصرف:</strong> يتعهد الطرف الأول بعدم التصرف في السيارة بأي نوع من أنواع التصرفات القانونية أو المادية سواء بالبيع أو الهبة أو الرهن أو إصدار توكيل للغير من تاريخ توقيع هذا العقد وأي تصرف يتم بالمخالفة لذلك يعد باطلاً بطلاناً مطلقاً ولا يسري في مواجهة الطرف الثاني.</p>
        <p><strong>البند الثامن - عدم الإلغاء أو الفسخ بإرادة منفردة:</strong> اتفق الطرفان على أن هذا العقد قرار نهائي وملزم للطرفين وغير قابل للإلغاء أو الفسخ أو التعديل بإرادة أي طرف منفردة ولا يجوز فسخه إلا باتفاق كتابي صريح بين الطرفين أو بحكم قضائي نهائي، وأي تصرف أو إعلان بالفسخ من جانب واحد كأن لم يكن ولا يرتب أي أثر قانوني.</p>
        <p><strong>البند التاسع - الضمان وعدم التعرض:</strong> يضمن الطرف الأول للطرف الثاني عدم التعرض له في حيازته أو ملكيته للسيارة سواء كان تعرضاً قانونياً أو مادياً وفي حال حدوث أي تعرض يلتزم بإزالته فوراً والتعويض الكامل عن كافة الأضرار.</p>
        <p><strong>البند العاشر - الشرط الجزائي:</strong> في حال إخلال الطرف الأول (البائع) بأي من التزاماته الواردة بهذا العقد يلتزم بدفع مبلغ وقدره (<span id="p_penalty_number"></span>) (فقط <span id="p_penalty_text"></span> لاغير) كشرط جزائي فوري ورد المبلغ المدفوع ثمن السيارة للطرف الثاني (المشتري) دون حاجة إلى إنذار أو إثبات ضرر مع احتفاظ الطرف الثاني بحقه في المطالبة بالتنفيذ العيني.</p>
        <p><strong>البند الحادي عشر - الإقرار بعدم الصورية:</strong> يقر الطرفان بأن هذا العقد جدي وحقيقي وليس صورياً وأنه لم يحرر بقصد التحايل أو إخفاء تصرف آخر.</p>
        <p><strong>البند الثاني عشر - المعاينة:</strong> يقر الطرف الثاني بأنه عاين السيارة المعاينة التامة النافية للجهالة وقبل شراءها بحالتها الراهنة.</p>
        <p><strong>البند الثالث عشر - الاختصاص القضائي:</strong> تختص <span id="p_court_name"></span> بنظر أي نزاع ينشأ عن هذا العقد.</p>
        <p><strong>البند الرابع عشر - نسخ العقد:</strong> حرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم.</p>

        <div class="signature-section">
            <div class="signature-box">
                <p>الطرف الأول (البائع)</p>
                <p>الاسم/ <span id="p_seller_name2"></span></p>
                <p>الرقم القومي/ <span id="p_seller_id2"></span></p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
            </div>
            <div class="signature-box">
                <p>الطرف الثاني (المشتري)</p>
                <p>الاسم/ <span id="p_buyer_name2"></span></p>
                <p>الرقم القومي/ <span id="p_buyer_id2"></span></p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
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
    s('p_buyer_name', f('buyer_name')); s('p_buyer_name2', f('buyer_name'));
    s('p_buyer_id', f('buyer_id')); s('p_buyer_id2', f('buyer_id'));
    s('p_buyer_address', f('buyer_address'));
    s('p_car_type', f('car_type')); s('p_car_type2', f('car_type'));
    s('p_car_model', f('car_model')); s('p_car_model2', f('car_model'));
    s('p_car_color', f('car_color')); s('p_car_color2', f('car_color'));
    s('p_car_chassis', f('car_chassis')); s('p_car_chassis2', f('car_chassis'));
    s('p_car_engine', f('car_engine')); s('p_car_engine2', f('car_engine'));
    s('p_car_plate', f('car_plate')); s('p_car_plate2', f('car_plate'));
    s('p_car_license_date', f('car_license_date'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_penalty_text', f('price_text')); /* Added to map the written price to the penalty text placeholder as in your original logic */
    s('p_court_name', f('court_name'));

    window.print();
}
</script>
@endpush

@endsection