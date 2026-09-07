<div>
    <style>
        /* ===== الحاوية والترويسة ===== */
        .create-page-container { padding: 2rem; max-width: 800px; margin: 0 auto; }
        
        .dashboard-header { 
            display: flex; justify-content: space-between; align-items: flex-start; 
            margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
        }
        .header-info h1 { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
        .header-info p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

        /* ===== البانل الملموم ===== */
        .form-wrapper { display: flex; justify-content: center; }
        .form-card { 
            background: #ffffff; border: 1px solid var(--border-color); 
            border-radius: 12px; padding: 2.5rem 2rem; width: 100%; max-width: 600px;
            box-shadow: var(--shadow-sm); transition: all 0.3s ease;
        }
        .form-card:hover { border-color: var(--gold-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }

        /* ===== الفورم ===== */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.8rem; font-weight: 700; color: var(--text-primary); font-size: 1rem; }
        
        .form-control { 
            width: 100%; padding: 1rem 1.2rem; border: 1px solid var(--border-color); 
            border-radius: 8px; background-color: var(--primary-bg); font-family: inherit; 
            font-size: 1rem; transition: all 0.2s; color: var(--text-primary);
        }
        .form-control:focus { 
            outline: none; border-color: var(--gold-accent); 
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); background-color: #ffffff;
        }
        .form-control::placeholder { color: var(--text-secondary); opacity: 0.7; }

        /* ===== التنبيهات والأخطاء ===== */
        .error-msg { font-size: 0.85rem; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; margin-top: 8px; font-weight: 600; }
        
        /* ===== أزرار التحكم ===== */
        .form-actions { 
            display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px dashed var(--border-color); padding-top: 1.5rem; 
        }
        
        .btn-cancel { 
            flex: 1; padding: 12px; background-color: transparent; color: var(--text-secondary); 
            border: 1px solid var(--border-color); border-radius: 8px; font-weight: 700; 
            cursor: pointer; transition: 0.2s; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; text-decoration: none;
        }
        .btn-cancel:hover { background-color: var(--primary-bg); color: var(--text-primary); border-color: var(--text-secondary); }
        
        .btn-save { 
            flex: 1; padding: 12px; background-color: var(--sidebar-bg); color: #ffffff; 
            border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
            transition: 0.3s; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; font-family: inherit; font-size: 1rem;
        }
        .btn-save:hover { background-color: var(--gold-accent); color: var(--sidebar-bg); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2); transform: translateY(-2px); }
        .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

        @media (max-width: 768px) {
            .create-page-container { padding: 1rem; }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column-reverse; }
            .btn-cancel, .btn-save { width: 100%; }
        }
    </style>

    <div class="create-page-container">

        <div class="dashboard-header">
            <div class="header-info">
                <h1><i class="fas fa-sitemap" style="color: var(--gold-accent); margin-left: 8px;"></i> إضافة جهة تقاضي</h1>
                <p>{{ __('تسجيل نوع أو جهة قضاء جديدة (مثل: القضاء العادي، الإداري، التجاري...)') }}</p>
            </div>
            <a href="{{ route('jurisdictions.index') }}" wire:navigate class="btn-cancel" style="flex: unset; padding: 10px 20px;">
                <i class="fas fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        <div class="form-wrapper">
            <div class="form-card">
                <form wire:submit="save">

                    <div class="form-group">
                        <label for="name" class="form-label">اسم نوع القضاء أو الجهة <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" id="name" wire:model="name" class="form-control" placeholder="{{ __('مثال: القضاء الإداري') }}" required autofocus>
                        @error('name')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('jurisdictions.index') }}" wire:navigate class="btn-cancel">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <button type="submit" class="btn-save" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save"><i class="fas fa-save"></i> حفظ الجهة</span>
                            <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> جاري الحفظ...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
