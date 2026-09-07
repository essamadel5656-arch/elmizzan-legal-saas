@extends('layouts.app')

@section('title', 'عقد بيع نهائي لشقة سكنية | ' . $appName)

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
            <h1><i class="fas fa-file-signature" style="color: var(--gold-accent); margin-left: 8px;"></i> عقد بيع نهائي لشقة سكنية</h1>
            <p>أدخل بيانات أطراف العقد وتفاصيل الشقة ثم اضغط على زر المعاينة والطباعة أدناه.</p>
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

        {{-- بيانات الشقة --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-building"></i> بيانات الشقة والعقار</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="apt_location">موقع الشقة (المدينة - الحي)</label>
                    <input type="text" id="apt_location" class="form-control" placeholder="مثال: أسوان الجديدة - حي الاقتصاد - المجاورة الأولى">
                </div>
                <div class="form-group">
                    <label class="form-label" for="apt_plot">رقم القطعة / العمارة</label>
                    <input type="text" id="apt_plot" class="form-control" placeholder="مثال: 145">
                </div>
                <div class="form-group">
                    <label class="form-label" for="apt_floor">الدور</label>
                    <input type="text" id="apt_floor" class="form-control" placeholder="مثال: الثاني علوي">
                </div>
                <div class="form-group">
                    <label class="form-label" for="apt_side">الجهة (يمين/يسار/واجهة)</label>
                    <input type="text" id="apt_side" class="form-control" placeholder="مثال: من ناحية اليمين صعوداً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="apt_rooms">مكونات الشقة (عدد الغرف)</label>
                    <input type="text" id="apt_rooms" class="form-control" placeholder="مثال: ثلاث حجرات وصالة ومنافعها">
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
                    <input type="text" id="price_number" class="form-control" placeholder="مثال: 500,000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">ثمن البيع (حروف)</label>
                    <input type="text" id="price_text" class="form-control" placeholder="مثال: خمسمائة ألف ريالاً عمانياً">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">التعويض الجزائي (أرقام)</label>
                    <input type="text" id="penalty_number" class="form-control" value="100000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">تاريخ تحرير العقد</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="court_name">المحكمة المختصة بالنزاعات</label>
                    <input type="text" id="court_name" class="form-control" value="محاكم أسوان بأنواعها">
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
        <h2>عقد بيع نهائي لشقة سكنية</h2>

        <p>إنه في يوم الموافق <span id="p_date"></span> حرر هذا العقد بين كلٍ من:</p>

        <p><strong>أولاً:</strong> السيد/ة <span id="p_seller_name"></span> - مصري الجنسية - مسلم الديانة - بالغ الرشد - مقيم بـ <span id="p_seller_address"></span>، ويحمل بطاقة رقم قومي (<span id="p_seller_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف أول بائع)</p>

        <p><strong>ثانياً:</strong> السيد/ة <span id="p_buyer_name"></span> - مصري الجنسية - مسلم الديانة - بالغ الرشد - مقيم بـ <span id="p_buyer_address"></span>، ويحمل بطاقة رقم قومي (<span id="p_buyer_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">(طرف ثاني مشتري)</p>

        <p><strong>التمهيد</strong></p>
        <p>يمتلك ويحوز الطرف الأول (البائع) شقة سكنية بمدينة <span id="p_apt_location"></span> بالقطعة رقم <span id="p_apt_plot"></span> بالدور <span id="p_apt_floor"></span> (<span id="p_apt_side"></span>)، ولرغبة الطرف الأول في بيع الشقة بالدور المذكور (<span id="p_apt_side2"></span>)، تلاقت إرادة الطرفين وأقرا بأهليتهما للتعاقد واتفقا على الآتي:</p>

        <p><strong>البند الأول:</strong> التمهيد السابق جزء لا يتجزأ من هذا العقد ومكملاً ومفسراً لبنوده.</p>
        
        <p><strong>البند الثاني:</strong> يقر الطرف الأول بأنه المالك الوحيد والفعلي دون منازع للعين (الشقة) المذكورة موضوع هذا العقد كما أنه هو الحائز الوحيد فعلياً لها والمسيطر عليها من دون منازع، حيث يضع يده عليها وضعاً مستقراً ومستمراً حتى تاريخ البيع.</p>
        
        <p><strong>البند الثالث:</strong> بموجب هذا العقد باع وأسقط وتنازل بكافة الضمانات القانونية والفعلية الطرف الأول إلى الطرف الثاني القابل لذلك شقة سكنية بالدور <span id="p_apt_floor2"></span> (<span id="p_apt_side3"></span>) مكونة من <span id="p_apt_rooms"></span> بالمرافق والملاحق بمدينة <span id="p_apt_location2"></span> بالقطعة رقم <span id="p_apt_plot2"></span>.</p>
        
        <p><strong>البند الرابع:</strong> تم هذا البيع بين الطرفين نظير ثمن إجمالي وقدره (<span id="p_price_number"></span>) (فقط <span id="p_price_text"></span> لا غير) دفعت بالكامل عداً ونقداً من يد ومال الطرف الثاني ليد الطرف الأول عند تحرير هذا العقد وبذات المجلس ويعتبر توقيع الطرف الأول على هذا العقد بمثابة مخالصة تامة باستلام كامل الثمن وبراءة ذمة المشتري.</p>
        
        <p><strong>البند الخامس:</strong> يقر الطرف الأول بأن العين المذكورة محل العقد لم يسبق له بيعها أو التصرف فيها من قبل وأن الشقة خالية من كافة الحقوق العينية الأصلية والتبعية كالرهن والاختصاص والامتياز وحقوق الانتفاع، وأنه يتحمل المسؤولية كاملة في حالة ظهور خلاف ذلك.</p>
        
        <p><strong>البند السادس:</strong> يقر الطرف الأول (البائع) بأنه يضمن تسليم الشقة وأنه يضمن عدم التعرض للطرف الثاني المتعاقد في هذه الشقة سواء منه أو من الغير سواء كان تعرضاً مادياً أو قانونياً.</p>
        
        <p><strong>البند السابع:</strong> يلتزم الطرف الأول بتسليم الطرف الثاني كافة مستندات ملكيته للشقة كما يلتزم بالحضور للإقرار بالبيع وصحة التوقيع أمام المحاكم بمختلف درجاتها وكذلك أمام جميع الجهات القضائية والشهر العقاري وجميع الجهات الحكومية وغيرها متى طلب منه ذلك.</p>
        
        <p><strong>البند الثامن:</strong> لا يجوز لأي من الطرفين الرجوع في البيع، وفي حالة إخلال أحد الطرفين أو عدم تنفيذ أي بند من البنود السابقة يلتزم الطرف المخل بتعويض اتفاقي نهائي غير خاضع لرقابة القضاء قدره (<span id="p_penalty_number"></span>) بالإضافة للمصروفات التي تحملها الطرف الآخر.</p>
        
        <p><strong>البند التاسع:</strong> يقر الطرفان بأن هذا العقد نهائي وبات ولا رجعة فيه وفي حالة وفاة أحدهم (لا قدر الله) يسري هذا العقد في مواجهة الورثة الشرعيين وخلفهم العام والخاص.</p>
        
        <p><strong>البند العاشر:</strong> ينعقد الاختصاص لـ <span id="p_court_name"></span> على مختلف درجاتها بنظر أي نزاع قد ينشأ بين الطرفين بصدد تنفيذ أو تفسير بنود هذا العقد، كما يقر كل من الطرفين بأن العنوان المذكور بصدر هذا العقد هو محله المختار كي تُرسل إليه جميع المراسلات والإعلانات.</p>
        
        <p><strong>البند الحادي عشر:</strong> تحرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم، وتم التوقيع عليه من الطرفين في حضور الشهود المذيلين أدناه.</p>

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
                <p>الشاهد الأول</p>
                <p>الاسم/ .........................................</p>
                <p>الرقم القومي/ .........................................</p>
                <p style="margin-top: 20px;">التوقيع/ .........................................</p>
            </div>
            <div class="signature-box">
                <p>الشاهد الثاني</p>
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
    s('p_apt_location', f('apt_location')); s('p_apt_location2', f('apt_location'));
    s('p_apt_plot', f('apt_plot')); s('p_apt_plot2', f('apt_plot'));
    s('p_apt_floor', f('apt_floor')); s('p_apt_floor2', f('apt_floor'));
    s('p_apt_side', f('apt_side')); s('p_apt_side2', f('apt_side')); s('p_apt_side3', f('apt_side'));
    s('p_apt_rooms', f('apt_rooms'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_court_name', f('court_name'));

    window.print();
}
</script>
@endpush
@endsection