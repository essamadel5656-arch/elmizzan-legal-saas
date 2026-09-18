@extends('layouts.app')

@section('title', 'عقد بيع نهائي (ورثة) | ' . $appName)

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
            <h1><i class="fas fa-file-contract" style="color: var(--gold-accent); margin-left: 8px;"></i> {{ __('عقد بيع نهائي (عن طريق الورثة)') }}</h1>
            <p>{{ __('أدخل بيانات أطراف العقد (الورثة والمشتري) وتفاصيل العقار، ثم اضغط على زر المعاينة والطباعة.') }}</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-right"></i> {{ __('العودة للمكتبة القانونية') }}
        </a>
    </div>

    <!-- فورم الإدخال (لا تظهر في الطباعة) -->
    <div class="no-print">
        
        {{-- بيانات الورثة (الطرف الأول) --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-users"></i> {{ __('بيانات الطرف الأول (البائعين - الورثة)') }}</div>
            </div>
            
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="deceased_name">{{ __('اسم المورث (المتوفى)') }}</label>
                    <input type="text" id="deceased_name" class="form-control" placeholder="{{ __('اسم المورث صاحب الأملاك') }}">
                </div>

                <!-- الوارث الأول -->
                <div class="form-group full" style="margin-top: 1rem; border-top: 1px dashed var(--border-color); padding-top: 1rem;">
                    <label class="form-label" for="heir1_name" style="color: var(--sidebar-bg);">{{ __('الوارث الأول - الاسم') }}</label>
                    <input type="text" id="heir1_name" class="form-control" placeholder="{{ __('الاسم رباعي') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="heir1_id">{{ __('الوارث الأول - الرقم القومي') }}</label>
                    <input type="text" id="heir1_id" class="form-control" placeholder="{{ __('14 رقم') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="heir1_address">{{ __('الوارث الأول - العنوان') }}</label>
                    <input type="text" id="heir1_address" class="form-control" placeholder="{{ __('محل الإقامة') }}">
                </div>

                <!-- الوارث الثاني -->
                <div class="form-group full" style="margin-top: 1rem; border-top: 1px dashed var(--border-color); padding-top: 1rem;">
                    <label class="form-label" for="heir2_name" style="color: var(--sidebar-bg);">{{ __('الوارث الثاني - الاسم') }} <span style="color: var(--text-secondary); font-weight: 500;">{{ __('(اختياري)') }}</span></label>
                    <input type="text" id="heir2_name" class="form-control" placeholder="{{ __('اتركه فارغاً إن لم يوجد') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="heir2_id">{{ __('الوارث الثاني - الرقم القومي') }}</label>
                    <input type="text" id="heir2_id" class="form-control" placeholder="{{ __('14 رقم') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="heir2_address">{{ __('الوارث الثاني - العنوان') }}</label>
                    <input type="text" id="heir2_address" class="form-control" placeholder="{{ __('محل الإقامة') }}">
                </div>

                <!-- الوارث الثالث -->
                <div class="form-group full" style="margin-top: 1rem; border-top: 1px dashed var(--border-color); padding-top: 1rem;">
                    <label class="form-label" for="heir3_name" style="color: var(--sidebar-bg);">{{ __('الوارث الثالث - الاسم') }} <span style="color: var(--text-secondary); font-weight: 500;">{{ __('(اختياري)') }}</span></label>
                    <input type="text" id="heir3_name" class="form-control" placeholder="{{ __('اتركه فارغاً إن لم يوجد') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="heir3_id">{{ __('الوارث الثالث - الرقم القومي') }}</label>
                    <input type="text" id="heir3_id" class="form-control" placeholder="{{ __('14 رقم') }}">
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
                    <input type="text" id="buyer_name" class="form-control" placeholder="{{ __('الاسم رباعي') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="buyer_id">{{ __('الرقم القومي للمشتري') }}</label>
                    <input type="text" id="buyer_id" class="form-control" placeholder="{{ __('14 رقم') }}">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="buyer_address">{{ __('عنوان المشتري') }}</label>
                    <input type="text" id="buyer_address" class="form-control" placeholder="{{ __('محل الإقامة بالتفصيل المذكور في البطاقة') }}">
                </div>
            </div>
        </div>

        {{-- بيانات العقار --}}
        <div class="form-card">
            <div class="form-card-title">
                <div class="title-with-icon"><i class="fas fa-building"></i> {{ __('بيانات العقار الموروث') }}</div>
            </div>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="property_desc">{{ __('وصف العقار وموقعه بالتفصيل') }}</label>
                    <textarea id="property_desc" class="form-control" placeholder="{{ __('مثال: قطعة أرض مباني مساحتها كذا... الكائنة بـ...') }}"></textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label" for="property_borders">{{ __('الحدود الأربعة (شمال - جنوب - شرق - غرب)') }}</label>
                    <textarea id="property_borders" class="form-control" placeholder="{{ __('الحد البحري: ... الحد القبلي: ... الحد الشرقي: ... الحد الغربي: ...') }}"></textarea>
                </div>
                <div class="form-group full">
                    <label class="form-label" for="property_origin">{{ __('كيف آلت الملكية للورثة البائعين') }}</label>
                    <input type="text" id="property_origin" class="form-control" value="{{ __('عن طريق الميراث الشرعي من مورثهم') }}">
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
                    <input type="text" id="price_number" class="form-control" placeholder="{{ __('مثال: 1,500,000') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="price_text">{{ __('ثمن البيع (كتابة)') }}</label>
                    <input type="text" id="price_text" class="form-control" placeholder="{{ __('مثال: مليون وخمسمائة ألف ريالاً عمانياً') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penalty_number">{{ __('الشرط الجزائي (أرقام)') }}</label>
                    <input type="text" id="penalty_number" class="form-control" value="150000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contract_date">{{ __('تاريخ العقد') }}</label>
                    <input type="date" id="contract_date" class="form-control">
                </div>
                <div class="form-group full">
                    <label class="form-label" for="court_name">{{ __('المحكمة المختصة بالنزاعات') }}</label>
                    <input type="text" id="court_name" class="form-control" value="{{ __('محكمة أسوان الابتدائية وجزئياتها') }}">
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

    <!-- منطقة الطباعة (تظهر في الطباعة فقط) -->
    <div class="print-area" id="printArea">
        <h2>{{ __('عقد بيع نهائي') }}</h2>

        <p>{{ __('إنه في يوم الموافق') }} <span id="p_date"></span> {{ __('حرر هذا العقد فيما بين كلٍ من:') }}</p>

        <p><strong>{{ __('أولاً: ورثة المرحوم/') }} <span id="p_deceased_name"></span>:</strong></p>
        <p>{{ __('1) السيد/ة') }} <span id="p_heir1_name"></span> {{ __('- مصري الجنسية - مسلم الديانة - بالغ سن الرشد - المقيم/') }} <span id="p_heir1_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_heir1_id"></span>)</p>
        
        <p id="heir2_block">{{ __('2) السيد/ة') }} <span id="p_heir2_name"></span> {{ __('- مصري الجنسية - مسلم الديانة - بالغ سن الرشد - المقيم/') }} <span id="p_heir2_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_heir2_id"></span>)</p>
        
        <p id="heir3_block">{{ __('3) السيد/ة') }} <span id="p_heir3_name"></span> {{ __('- ويحمل بطاقة رقم قومي (') }}<span id="p_heir3_id"></span>)</p>
        
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">{{ __('(أفراد الطرف الأول بائعين)') }}</p>

        <p><strong>{{ __('ثانياً: السيد/ة') }} </strong> <span id="p_buyer_name"></span> {{ __('- مصري الجنسية - مسلم الديانة - بالغ سن الرشد - المقيم/') }} <span id="p_buyer_address"></span>{{ __('، ويحمل بطاقة رقم قومي (') }}<span id="p_buyer_id"></span>)</p>
        <p style="text-align: left; font-weight: bold; margin-bottom: 1.5rem;">{{ __('(الطرف الثاني مشتري)') }}</p>

        <p><strong>{{ __('بند تمهيدي:') }}</strong> {{ __('يمتلك أفراد الطرف الأول (بائعين)') }} <span id="p_property_desc"></span>{{ __('، وحدودها كالتالي:') }} <span id="p_property_borders"></span>{{ __('. وبعد أن أقر الطرفان بأهليتهما للتصرف والتعاقد اتفقا على ما يلي:') }}</p>

        <p><strong>{{ __('البند الأول:') }}</strong> {{ __('يعتبر التمهيد السابق جزءاً لا يتجزأ من هذا العقد مكملاً ومفسراً لبنوده.') }}</p>
        <p><strong>{{ __('البند الثاني:') }}</strong> {{ __('باع وأسقط وتنازل وبكافة الضمانات الفعلية والقانونية أفراد الطرف الأول (البائعين) للطرف الثاني (المشتري)') }} <span id="p_property_desc2"></span>.</p>
        <p><strong>{{ __('البند الثالث:') }}</strong> {{ __('تم هذا البيع مقابل مبلغ إجمالي وقدره (') }}<span id="p_price_number"></span>{{ __(') (فقط') }} <span id="p_price_text"></span> {{ __('لاغير) قد تم دفعها بالكامل من يد ومال الطرف الثاني (المشتري) ليد أفراد الطرف الأول (البائعين) بمجلس العقد عداً ونقداً، ويعتبر توقيعهم على هذا العقد بمثابة مخالصة تامة ونهائية بقبض كامل الثمن.') }}</p>
        <p><strong>{{ __('البند الرابع:') }}</strong> {{ __('يقر أفراد الطرف الأول (البائعين) بأن العقار موضوع هذا العقد آلت إليهم ملكيته') }} <span id="p_property_origin"></span>.</p>
        <p><strong>{{ __('البند الخامس:') }}</strong> {{ __('يقر أفراد الطرف الأول (البائعين) بخلو العقار المبيع من أي حقوق عينية أصلية أو تبعية، ولم يسبق لهم التصرف فيها بالبيع أو الرهن أو التنازل.') }}</p>
        <p><strong>{{ __('البند السادس:') }}</strong> {{ __('يقر الطرف الثاني (المشتري) بأنه عاين العقار المبيع المعاينة التامة النافية للجهالة وقبل الشراء بحالته التي هي عليها وقت التعاقد.') }}</p>
        <p><strong>{{ __('البند السابع:') }}</strong> {{ __('يلتزم أفراد الطرف الأول (البائعين) في حالة ظهور أي وارث غير من ذُكروا بهذا العقد، بدفع ما يساوي نصيبه شرعاً وقانوناً دون الرجوع على المشتري.') }}</p>
        <p><strong>{{ __('البند الثامن:') }}</strong> {{ __('يلتزم أفراد الطرف الأول (البائعين) بتقديم كافة المستندات الدالة على ملكيتهم للعقار المبيع للطرف الثاني متى طُلب منهم ذلك.') }}</p>
        <p><strong>{{ __('البند التاسع:') }}</strong> {{ __('يتعهد أفراد الطرف الأول (البائعين) بمثولهم أمام جميع الجهات الحكومية والقضائية للإقرار بصحة توقيعهم ونفاذ العقد متى طُلب منهم ذلك.') }}</p>
        <p><strong>{{ __('البند العاشر:') }}</strong> {{ __('يلتزم أفراد الطرف الأول (البائعين) بالتنازل عن عدادات الكهرباء والمياه والغاز الخاصة بالعقار للطرف الثاني (المشتري).') }}</p>
        <p><strong>{{ __('البند الحادي عشر:') }}</strong> {{ __('يتعهد ويلتزم أفراد الطرف الأول (البائعين) بضمان عدم التعرض المادي والقانوني للطرف الثاني (المشتري) منهم أو من الغير في العقار المبيع موضوع هذا العقد.') }}</p>
        <p><strong>{{ __('البند الثاني عشر:') }}</strong> {{ __('لا يجوز لأفراد الطرف الأول (البائعين) أو لورثتهم أن يطعنوا بالتزوير أو بالجهالة مستقبلاً على توقيع أي فرد منهم على هذا العقد.') }}</p>
        <p><strong>{{ __('البند الثالث عشر:') }}</strong> {{ __('تختص') }} <span id="p_court_name"></span> {{ __('بالفصل في أي نزاع قد ينشأ بشأن تنفيذ أو تفسير أي بند من بنود هذا العقد.') }}</p>
        <p><strong>{{ __('البند الرابع عشر:') }}</strong> {{ __('حرر هذا العقد من عدة نسخ بيد كل طرف نسخة للعمل بموجبها عند اللزوم.') }}</p>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>{{ __('أفراد الطرف الأول (البائعين)') }}</strong></p>
                
                <p style="text-align: right; margin-top:10px;">{{ __('1) الاسم/') }} <span id="p_heir1_name2"></span></p>
                <p style="text-align: right;">{{ __('التوقيع/ ..................... البصمة/ .....................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/') }} <span id="p_heir1_id2"></span></p>
                
                <p style="text-align: right; margin-top:15px;">{{ __('2) الاسم/') }} <span id="p_heir2_name2"></span></p>
                <p style="text-align: right;">{{ __('التوقيع/ ..................... البصمة/ .....................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/') }} <span id="p_heir2_id2"></span></p>
                
                <p style="text-align: right; margin-top:15px;">{{ __('3) الاسم/') }} <span id="p_heir3_name2"></span></p>
                <p style="text-align: right;">{{ __('التوقيع/ ..................... البصمة/ .....................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/') }} <span id="p_heir3_id2"></span></p>
            </div>
            
            <div class="signature-box">
                <p><strong>{{ __('الطرف الثاني (المشتري)') }}</strong></p>
                <p style="text-align: right; margin-top:10px;">{{ __('الاسم/') }} <span id="p_buyer_name2"></span></p>
                <p style="text-align: right;">{{ __('التوقيع/ .....................') }}</p>
                <p style="text-align: right;">{{ __('البصمة/ .....................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/') }} <span id="p_buyer_id2"></span></p>
            </div>
        </div>

        <div class="signature-section" style="margin-top: 2rem;">
            <div class="signature-box">
                <p><strong>{{ __('شاهد أول') }}</strong></p>
                <p style="text-align: right;">{{ __('الاسم/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('التوقيع/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/ .........................................') }}</p>
            </div>
            <div class="signature-box">
                <p><strong>{{ __('شاهد ثاني') }}</strong></p>
                <p style="text-align: right;">{{ __('الاسم/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('التوقيع/ .........................................') }}</p>
                <p style="text-align: right;">{{ __('الرقم القومي/ .........................................') }}</p>
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
    s('p_deceased_name', f('deceased_name'));
    
    s('p_heir1_name', f('heir1_name')); s('p_heir1_name2', f('heir1_name'));
    s('p_heir1_id', f('heir1_id')); s('p_heir1_id2', f('heir1_id'));
    s('p_heir1_address', f('heir1_address'));
    
    s('p_heir2_name', f('heir2_name')); s('p_heir2_name2', f('heir2_name'));
    s('p_heir2_id', f('heir2_id')); s('p_heir2_id2', f('heir2_id'));
    s('p_heir2_address', f('heir2_address'));
    
    s('p_heir3_name', f('heir3_name')); s('p_heir3_name2', f('heir3_name'));
    s('p_heir3_id', f('heir3_id')); s('p_heir3_id2', f('heir3_id'));
    
    s('p_buyer_name', f('buyer_name')); s('p_buyer_name2', f('buyer_name'));
    s('p_buyer_id', f('buyer_id')); s('p_buyer_id2', f('buyer_id'));
    s('p_buyer_address', f('buyer_address'));
    
    s('p_property_desc', f('property_desc')); s('p_property_desc2', f('property_desc'));
    s('p_property_borders', f('property_borders'));
    s('p_property_origin', f('property_origin'));
    s('p_price_number', f('price_number'));
    s('p_price_text', f('price_text'));
    s('p_penalty_number', f('penalty_number'));
    s('p_court_name', f('court_name'));

    // Hide empty heirs cleanly in the print area
    if (!f('heir2_name')) document.getElementById('heir2_block').style.display = 'none';
    else document.getElementById('heir2_block').style.display = 'block';

    if (!f('heir3_name')) document.getElementById('heir3_block').style.display = 'none';
    else document.getElementById('heir3_block').style.display = 'block';

    window.print();
}
</script>
@endpush

@endsection