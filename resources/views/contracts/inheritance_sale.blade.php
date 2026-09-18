@extends('layouts.app')

@section('title', 'عقد بيع حصة إرث في منزل | ' . $appName)

@push('styles')
<style>
    /* ===== إعدادات الطباعة (ممنوع التعديل عليها) ===== */
    @media print {
        .no-print { display: none !important; }
        .print-container { padding: 0; margin: 0; }
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
        .print-area .signature-section { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 3rem; }
        .print-area .signature-box { text-align: center; border-top: 1px solid black; padding-top: 0.5rem; }
        .print-area .signature-box p { margin: 0.2rem 0; font-weight: bold; }
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
    textarea.form-control { resize: vertical; min-height: 80px; }

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
            <h1><i class="fas fa-home" style="color: var(--gold-accent); margin-left: 8px;"></i> {{ __('عقد بيع حصة إرث في منزل') }}</h1>
            <p>{{ __('أدخل بيانات أطراف العقد وتفاصيل الحصة الموروثة ثم اضغط على زر المعاينة والطباعة أدناه.') }}</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> {{ __('العودة للمكتبة القانونية') }}
        </a>
    </div>

    <!-- فورم الإدخال (لا تظهر في الطباعة) -->
    <div class="no-print">
        
        {{-- بيانات البائع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-tag"></i> {{ __('بيانات الطرف الأول (البائع)') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="seller_name">{{ __('اسم البائع') }}</label>
                    <input type="text" id="seller_name" class="form-control" placeholder="{{ __('الاسم رباعياً') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_id">{{ __('الرقم القومي للبائع') }}</label>
                    <input type="text" id="seller_id" class="form-control" placeholder="{{ __('14 رقم') }}" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="seller_job">{{ __('وظيفة البائع') }}</label>
                    <input type="text" id="seller_job" class="form-control" placeholder="{{ __('مثال: موظف، مهندس...') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="seller_address">{{ __('عنوان البائع') }}</label>
                    <input type="text" id="seller_address" class="form-control" placeholder="{{ __('محل الإقامة بالتفصيل') }}">
                </div>
            </div>
        </div>

        {{-- بيانات المشتري --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-user-check"></i> {{ __('بيانات الطرف الثاني (المشتري)') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="buyer_name">{{ __('اسم المشتري') }}</label>
                    <input type="text" id="buyer_name" class="form-control" placeholder="{{ __('الاسم رباعياً') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_id">{{ __('الرقم القومي للمشتري') }}</label>
                    <input type="text" id="buyer_id" class="form-control" placeholder="{{ __('14 رقم') }}" maxlength="14">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">{{ __('عنوان المشتري') }}</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="{{ __('محل الإقامة بالتفصيل') }}">
                </div>
            </div>
        </div>

        {{-- بيانات العقار الموروث --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-building"></i> {{ __('بيانات العقار والحصة الموروثة') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="property_location">{{ __('موقع المنزل') }}</label>
                    <input type="text" id="property_location" class="form-control" placeholder="{{ __('الحي - المحافظة') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="property_address">{{ __('رقم المنزل والشارع') }}</label>
                    <input type="text" id="property_address" class="form-control" placeholder="{{ __('الشارع ورقم العقار') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="property_area">{{ __('المساحة الكلية للعقار') }}</label>
                    <input type="text" id="property_area" class="form-control" placeholder="{{ __('مثال: 97.27م2') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="property_borders">{{ __('الحدود (شرق - غرب - بحري - قبلي)') }}</label>
                    <textarea id="property_borders" class="form-control" rows="2" placeholder="{{ __('الحد الشرقي: ... الحد الغربي: ... الحد البحري: ... الحد القبلي: ...') }}"></textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label" for="inheritor_name">{{ __('اسم المورث (آلت بالميراث عن)') }}</label>
                    <input type="text" id="inheritor_name" class="form-control" placeholder="{{ __('اسم صاحب الأملاك الأصلي المتوفى') }}">
                </div>
            </div>
        </div>

        {{-- بيانات البيع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-handshake"></i> {{ __('تفاصيل البيع والتعاقد') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="price_number">{{ __('ثمن البيع (أرقام)') }}</label>
                    <input type="text" id="price_number" class="form-control" placeholder="{{ __('مثال: 1,200,000') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">{{ __('ثمن البيع (كتابة)') }}</label>
                    <input type="text" id="price_text" class="form-control" placeholder="{{ __('مثال: مليون ومائتان ألف ريالاً عمانياً') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">{{ __('التعويض الجزائي (أرقام)') }}</label>
                    <input type="text" id="penalty_number" class="form-control" value="100000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="court_name">{{ __('المحكمة المختصة بالنزاعات') }}</label>
                    <input type="text" id="court_name" class="form-control" value="{{ __('محكمة أسوان الجزئية') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="contract_date">{{ __('تاريخ العقد') }}</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
            </div>
        </div>

        {{-- زر الطباعة --}}
        <div class="form-actions">
            <button class="btn-print-action" onclick="printContract()">
                <i class="fas fa-print fa-lg"></i> {{ __('معاينة وطباعة العقد الموثق') }}
            </button>
        </div>

    </div>

    <!-- منطقة الطباعة (تظهر في الطباعة فقط ومطابقة تماماً للأصل) -->
    <div class="print-area" id="printArea">
        <h2>{{ __('عقد بيع حصة إرث في منزل') }}</h2>

        <p><strong>{{ __('أولاً: السيد/') }}</strong> <span id="p_seller_name"></span> - <span id="p_seller_job"></span> {{ __('- المقيم في/') }} <span id="p_seller_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_seller_id"></span>)</p>
        <p><strong>{{ __('طرف أول (بائع)') }}</strong></p>

        <p><strong>{{ __('ثانياً: السيد/') }}</strong> <span id="p_buyer_name"></span> {{ __('- المقيم/') }} <span id="p_buyer_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_buyer_id"></span>)</p>
        <p><strong>{{ __('طرف ثاني (مشتري)') }}</strong></p>

        <p>{{ __('بعد أن أقر الطرفان بأهليتهما القانونية للتعاقد وعلى إبرام مثل هذا التصرف اتفقا على ما يلي:') }}</p>

        <p><strong>{{ __('تمهيد') }}</strong></p>
        <p>{{ __('يمتلك الطرف الأول حصة شائعة في منزل بناحية') }} <span id="p_property_location"></span>{{ __('، وهو عبارة عن / منزل') }} <span id="p_property_address"></span> {{ __('بمساحة كلية') }} <span id="p_property_area"></span>{{ __('، وحدوده كالآتي:') }} <span id="p_property_borders"></span></p>
        <p>{{ __('وقد آلت الملكية بالميراث الشرعي عن المرحومة/') }} <span id="p_inheritor_name"></span>{{ __('، وحيث أن الطرف الأول عرض هذه الحصة للبيع وتقدم الطرف الثاني لشرائها واتفقا على ما يلي:') }}</p>

        <p><strong>{{ __('البند الأول:') }}</strong> {{ __('يعتبر التمهيد السابق جزء لا يتجزأ من هذا العقد.') }}</p>
        <p><strong>{{ __('البند الثاني:') }}</strong> {{ __('باع الطرف الأول وأسقط وتنازل بموجب هذا العقد وبكافة الضمانات الفعلية والقانونية ما هو عبارة عن حصة شائعة في منزل بناحية') }} <span id="p_property_location2"></span>{{ __('، وهو عبارة عن / منزل') }} <span id="p_property_address2"></span> {{ __('بمساحة كلية') }} <span id="p_property_area2"></span>.</p>
        <p><strong>{{ __('البند الثالث:') }}</strong> {{ __('تم هذا البيع بمبلغ إجمالي وقدره (') }}<span id="p_price_number"></span>{{ __(') (فقط') }} <span id="p_price_text"></span> {{ __('لاغير) دفعه الطرف الثاني عداً ونقداً بأكمله للطرف الأول في مجلس هذا العقد، ويعتبر توقيع الطرف الأول على هذا العقد بمثابة مخالصة بدفع كامل الثمن.') }}</p>
        <p><strong>{{ __('البند الرابع:') }}</strong> {{ __('للطرف الثاني منفعة الحصة المبيعة اعتباراً من تاريخ هذا العقد وتم إخطار القائم على إدارة المال الشائع بذلك.') }}</p>
        <p><strong>{{ __('البند الخامس:') }}</strong> {{ __('يقر الطرف الأول بخلو المبيع من كافة الحقوق العينية الأصلية والتبعية ويقر بأنه لم يتصرف قبل تاريخ هذا العقد في الحصة المبيعة أو في أية جزء منها وأنه يضمن للطرف الثاني عدم التعرض القانوني الصادر منه أو من الغير.') }}</p>
        <p><strong>{{ __('البند السادس:') }}</strong> {{ __('يقر الطرف الأول بأن باقي الملاك على الشيوع ورثة المرحومة/') }} <span id="p_inheritor_name2"></span>.</p>
        <p><strong>{{ __('البند السابع:') }}</strong> {{ __('يقر الطرف الثاني بأنه عاين الحصة موضوع هذا العقد المعاينة التامة النافية للجهالة شرعاً وأنه قبل شراءها بحالتها الراهنة.') }}</p>
        <p><strong>{{ __('البند الثامن:') }}</strong> {{ __('يقر الطرفان بأن العنوان الوارد بهذا العقد هو عنوان محل الإقامة كل منهما وهو المعول عليه فيما يتعلق بالإعلانات والإخطارات التي قد يتطلبها تنفيذ هذا العقد.') }}</p>
        <p><strong>{{ __('البند التاسع:') }}</strong> {{ __('إذا أخل أي طرف من أطراف هذا العقد بأي التزام من الالتزامات المفروضة عليه ببنود هذا العقد يلتزم بدفع تعويض وقدره (') }}<span id="p_penalty_number"></span>{{ __(') للطرف الآخر ولا يخضع هذا التعويض لتقدير القضاء فضلاً عن صحة هذا العقد ونفاذه.') }}</p>
        <p><strong>{{ __('البند العاشر:') }}</strong> {{ __('يتعهد الطرف الأول بالمثول أمام الشهر العقاري المختص للتوقيع على العقد النهائي أو الحضور بنفسه أو بوكيل عنه للتصديق على هذا العقد ليكون الحكم الصادر في الدعوى أساساً صالحاً للتسجيل ونقل الملكية وعلى أن تكون كافة الرسوم والمصروفات طبقاً للقانون.') }}</p>
        <p><strong>{{ __('البند الحادي عشر:') }}</strong> {{ __('تختص') }} <span id="p_court_name"></span> {{ __('بالفصل في أي نزاع ينشأ بشأن تنفيذ أو تفسير أو صحة ونفاذ هذا العقد.') }}</p>
        <p><strong>{{ __('البند الثاني عشر:') }}</strong> {{ __('حرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم.') }}</p>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>{{ __('الطرف الأول (البائع)') }}</strong></p>
                <p>{{ __('الاسم/') }} <span id="p_seller_name2"></span></p>
                <p>{{ __('رقم قومي/') }} <span id="p_seller_id2"></span></p>
                <p>{{ __('التوقيع/') }}</p>
            </div>
            <div class="signature-box">
                <p><strong>{{ __('الطرف الثاني (المشتري)') }}</strong></p>
                <p>{{ __('الاسم/') }} <span id="p_buyer_name2"></span></p>
                <p>{{ __('رقم قومي/') }} <span id="p_buyer_id2"></span></p>
                <p>{{ __('التوقيع/') }}</p>
            </div>
        </div>

        <div class="signature-section" style="margin-top: 2rem;">
            <div class="signature-box">
                <p><strong>{{ __('شاهد أول') }}</strong></p>
                <p>{{ __('الاسم/') }}</p>
                <p>{{ __('رقم قومي/') }}</p>
                <p>{{ __('التوقيع/') }}</p>
            </div>
            <div class="signature-box">
                <p><strong>{{ __('شاهد ثاني') }}</strong></p>
                <p>{{ __('الاسم/') }}</p>
                <p>{{ __('رقم قومي/') }}</p>
                <p>{{ __('التوقيع/') }}</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function printContract() {
    const fields = {
        seller_name: document.getElementById('seller_name').value,
        seller_job: document.getElementById('seller_job').value,
        seller_address: document.getElementById('seller_address').value,
        seller_id: document.getElementById('seller_id').value,
        buyer_name: document.getElementById('buyer_name').value,
        buyer_address: document.getElementById('buyer_address').value,
        buyer_id: document.getElementById('buyer_id').value,
        property_location: document.getElementById('property_location').value,
        property_address: document.getElementById('property_address').value,
        property_area: document.getElementById('property_area').value,
        property_borders: document.getElementById('property_borders').value,
        inheritor_name: document.getElementById('inheritor_name').value,
        price_number: document.getElementById('price_number').value,
        price_text: document.getElementById('price_text').value,
        penalty_number: document.getElementById('penalty_number').value,
        court_name: document.getElementById('court_name').value,
    };

    document.getElementById('p_seller_name').textContent = fields.seller_name;
    document.getElementById('p_seller_name2').textContent = fields.seller_name;
    document.getElementById('p_seller_job').textContent = fields.seller_job;
    document.getElementById('p_seller_address').textContent = fields.seller_address;
    document.getElementById('p_seller_id').textContent = fields.seller_id;
    document.getElementById('p_seller_id2').textContent = fields.seller_id;
    document.getElementById('p_buyer_name').textContent = fields.buyer_name;
    document.getElementById('p_buyer_name2').textContent = fields.buyer_name;
    document.getElementById('p_buyer_address').textContent = fields.buyer_address;
    document.getElementById('p_buyer_id').textContent = fields.buyer_id;
    document.getElementById('p_buyer_id2').textContent = fields.buyer_id;
    document.getElementById('p_property_location').textContent = fields.property_location;
    document.getElementById('p_property_location2').textContent = fields.property_location;
    document.getElementById('p_property_address').textContent = fields.property_address;
    document.getElementById('p_property_address2').textContent = fields.property_address;
    document.getElementById('p_property_area').textContent = fields.property_area;
    document.getElementById('p_property_area2').textContent = fields.property_area;
    document.getElementById('p_property_borders').textContent = fields.property_borders;
    document.getElementById('p_inheritor_name').textContent = fields.inheritor_name;
    document.getElementById('p_inheritor_name2').textContent = fields.inheritor_name;
    document.getElementById('p_price_number').textContent = fields.price_number;
    document.getElementById('p_price_text').textContent = fields.price_text;
    document.getElementById('p_penalty_number').textContent = fields.penalty_number;
    document.getElementById('p_court_name').textContent = fields.court_name;

    window.print();
}
</script>
@endpush

@endsection