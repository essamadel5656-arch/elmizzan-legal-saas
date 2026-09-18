@extends('layouts.app')

@section('title', 'عقد بيع وتنازل | ' . $appName)

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
        .signature-box { text-align: center; border-top: 1px solid black; padding-top: 0.5rem; }
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
            <h1><i class="fas fa-file-signature" style="color: var(--gold-accent); margin-left: 8px;"></i> {{ __('عقد بيع وتنازل') }}</h1>
            <p>{{ __('أدخل بيانات أطراف العقد وتفاصيل الشيء المبيع ثم اضغط على زر المعاينة والطباعة أدناه.') }}</p>
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
                <div class="title-with-icon"><i class="fas fa-user-tag"></i> {{ __('بيانات الطرف الأول (البائع / المتنازل)') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="seller_name">{{ __('اسم البائع') }}</label>
                    <input type="text" id="seller_name" class="form-control" placeholder="{{ __('الاسم رباعي') }}">
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
                <div class="title-with-icon"><i class="fas fa-user-check"></i> {{ __('بيانات الطرف الثاني (المشتري / المتنازل إليه)') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="buyer_name">{{ __('اسم المشتري') }}</label>
                    <input type="text" id="buyer_name" class="form-control" placeholder="{{ __('الاسم رباعي') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_id">{{ __('الرقم القومي للمشتري') }}</label>
                    <input type="text" id="buyer_id" class="form-control" placeholder="{{ __('14 رقم') }}" maxlength="14">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_job">{{ __('وظيفة المشتري') }}</label>
                    <input type="text" id="buyer_job" class="form-control" placeholder="{{ __('مثال: دكتور، أعمال حرة...') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">{{ __('عنوان المشتري') }}</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="{{ __('محل الإقامة بالتفصيل') }}">
                </div>
            </div>
        </div>

        {{-- بيانات الشيء المبيع --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-box-open"></i> {{ __('بيانات العقار / الشيء المبيع') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="property_desc">{{ __('وصف المبيع بدقة') }}</label>
                    <textarea id="property_desc" class="form-control" rows="3" placeholder="{{ __('وصف تفصيلي للعقار أو الشيء المبيع ومشتملاته...') }}"></textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label" for="property_location">{{ __('موقع المبيع') }}</label>
                    <input type="text" id="property_location" class="form-control" placeholder="{{ __('المكان أو العنوان الخاص بالشيء المبيع') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="property_origin">{{ __('مصدر الملكية (كيف آلت للبائع)') }}</label>
                    <input type="text" id="property_origin" class="form-control" placeholder="{{ __('مثال: بموجب عقد شراء مسجل، عن طريق الميراث...') }}">
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
                    <input type="text" id="price_number" class="form-control" placeholder="{{ __('مثال: 500,000') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">{{ __('ثمن البيع (كتابة)') }}</label>
                    <input type="text" id="price_text" class="form-control" placeholder="{{ __('مثال: خمسمائة ألف جنيهاً مصرياً') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">{{ __('التعويض الجزائي (أرقام)') }}</label>
                    <input type="text" id="penalty_number" class="form-control" value="100000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">{{ __('تاريخ العقد') }}</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="court_name">{{ __('المحكمة المختصة بالنزاعات') }}</label>
                    <input type="text" id="court_name" class="form-control" value="{{ __('محكمة أسوان الجزئية') }}">
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
        <h2>{{ __('عقد بيع وتنازل') }}</h2>

        <p>{{ __('إنه في يوم الموافق') }} <span id="p_date"></span> {{ __('تم تحرير هذا العقد بين كلٍ من:') }}</p>

        <p><strong>{{ __('أولاً: السيد/') }}</strong> <span id="p_seller_name"></span> - <span id="p_seller_job"></span> {{ __('- المقيم في/') }} <span id="p_seller_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_seller_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">{{ __('طرف أول (بائع ومتنازل)') }}</p>

        <p><strong>{{ __('ثانياً: السيد/') }}</strong> <span id="p_buyer_name"></span> - <span id="p_buyer_job"></span> {{ __('- المقيم في/') }} <span id="p_buyer_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_buyer_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">{{ __('طرف ثاني (مشتري ومتنازل إليه)') }}</p>

        <p>{{ __('بعد أن أقر الطرفان بأهليتهما القانونية للتعاقد اتفقا على ما يلي:') }}</p>

        <p><strong>{{ __('تمهيد:') }}</strong> {{ __('يمتلك الطرف الأول') }} <span id="p_property_desc"></span>{{ __('، الكائن في') }} <span id="p_property_location"></span>{{ __('، وقد آلت إليه الملكية') }} <span id="p_property_origin"></span>{{ __('، وحيث أن الطرف الأول عرض هذا المبيع للبيع والتنازل وتقدم الطرف الثاني لشرائه اتفقا على ما يلي:') }}</p>

        <p><strong>{{ __('البند الأول:') }}</strong> {{ __('يعتبر التمهيد السابق جزءاً لا يتجزأ من هذا العقد.') }}</p>
        <p><strong>{{ __('البند الثاني:') }}</strong> {{ __('باع وأسقط وتنازل الطرف الأول للطرف الثاني بكافة الضمانات الفعلية والقانونية عن') }} <span id="p_property_desc2"></span> {{ __('الكائن في') }} <span id="p_property_location2"></span>.</p>
        <p><strong>{{ __('البند الثالث:') }}</strong> {{ __('تم هذا البيع بمبلغ إجمالي وقدره (') }}<span id="p_price_number"></span>{{ __(') (فقط') }} <span id="p_price_text"></span> {{ __('لاغير) دفعه الطرف الثاني عداً ونقداً بأكمله للطرف الأول في مجلس هذا العقد، ويعتبر توقيع الطرف الأول على هذا العقد بمثابة مخالصة بدفع كامل الثمن.') }}</p>
        <p><strong>{{ __('البند الرابع:') }}</strong> {{ __('للطرف الثاني حق الانتفاع بالمبيع اعتباراً من تاريخ هذا العقد.') }}</p>
        <p><strong>{{ __('البند الخامس:') }}</strong> {{ __('يقر الطرف الأول بخلو المبيع من كافة الحقوق العينية الأصلية والتبعية ويقر بأنه لم يتصرف قبل تاريخ هذا العقد في المبيع أو في أي جزء منه.') }}</p>
        <p><strong>{{ __('البند السادس:') }}</strong> {{ __('يقر الطرف الثاني بأنه عاين المبيع موضوع هذا العقد المعاينة التامة النافية للجهالة شرعاً وأنه قبل شراءه بحالته الراهنة.') }}</p>
        <p><strong>{{ __('البند السابع:') }}</strong> {{ __('يلتزم الطرف الأول بالمثول أمام الجهات المختصة للتوقيع على العقد النهائي أو التصديق على هذا العقد متى طُلب منه ذلك.') }}</p>
        <p><strong>{{ __('البند الثامن:') }}</strong> {{ __('إذا أخل أي طرف بالتزاماته يلتزم بدفع تعويض وقدره (') }}<span id="p_penalty_number"></span>{{ __(') للطرف الآخر.') }}</p>
        <p><strong>{{ __('البند التاسع:') }}</strong> {{ __('تختص') }} <span id="p_court_name"></span> {{ __('بالفصل في أي نزاع ينشأ بشأن هذا العقد.') }}</p>
        <p><strong>{{ __('البند العاشر:') }}</strong> {{ __('حرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم.') }}</p>

        <div class="signature-section">
            <div class="signature-box">
                <p>{{ __('الطرف الأول (البائع)') }}</p>
                <p style="text-align: right; margin-top: 10px;">{{ __('الاسم/') }} <span id="p_seller_name2"></span></p>
                <p style="text-align: right;">{{ __('رقم قومي/') }} <span id="p_seller_id2"></span></p>
                <p style="text-align: right; margin-top: 20px;">{{ __('التوقيع/ .........................................') }}</p>
            </div>
            <div class="signature-box">
                <p>{{ __('الطرف الثاني (المشتري)') }}</p>
                <p style="text-align: right; margin-top: 10px;">{{ __('الاسم/') }} <span id="p_buyer_name2"></span></p>
                <p style="text-align: right;">{{ __('رقم قومي/') }} <span id="p_buyer_id2"></span></p>
                <p style="text-align: right; margin-top: 20px;">{{ __('التوقيع/ .........................................') }}</p>
            </div>
        </div>

        <div class="signature-section" style="margin-top: 3rem;">
            <div class="signature-box">
                <p>{{ __('شاهد أول') }}</p>
                <p style="text-align: right; margin-top: 10px;">{{ __('الاسم/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('رقم قومي/ .........................................') }}</p>
                <p style="text-align: right; margin-top: 20px;">{{ __('التوقيع/ .........................................') }}</p>
            </div>
            <div class="signature-box">
                <p>{{ __('شاهد ثاني') }}</p>
                <p style="text-align: right; margin-top: 10px;">{{ __('الاسم/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('رقم قومي/ .........................................') }}</p>
                <p style="text-align: right; margin-top: 20px;">{{ __('التوقيع/ .........................................') }}</p>
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
    s('p_seller_job', f('seller_job'));
    s('p_buyer_name', f('buyer_name')); s('p_buyer_name2', f('buyer_name'));
    s('p_buyer_id', f('buyer_id')); s('p_buyer_id2', f('buyer_id'));
    s('p_buyer_address', f('buyer_address'));
    s('p_buyer_job', f('buyer_job'));
    s('p_property_desc', f('property_desc')); s('p_property_desc2', f('property_desc'));
    s('p_property_location', f('property_location')); s('p_property_location2', f('property_location'));
    s('p_property_origin', f('property_origin'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_court_name', f('court_name'));

    window.print();
}
</script>
@endpush

@endsection