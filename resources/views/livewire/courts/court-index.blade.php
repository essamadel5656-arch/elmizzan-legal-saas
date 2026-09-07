<div>
    <style>
        /* ===== الحاوية والترويسة ===== */
        .courts-page-container { padding: 2rem; }
        
        .dashboard-header { 
            display: flex; justify-content: space-between; align-items: center; 
            flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; 
        }
        
        .welcome-title { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; }
        .date-text { color: var(--text-secondary); font-size: 0.95rem; }

        /* ===== زر الإضافة الرئيسي (الموحد) ===== */
        .btn-add-new {
            display: inline-flex; align-items: center; gap: 12px;
            background-color: var(--sidebar-bg); color: #ffffff;
            padding: 6px 24px 6px 8px; border-radius: 50px;
            text-decoration: none; font-weight: 700; font-size: 0.95rem;
            transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
            border: 2px solid var(--sidebar-bg);
        }
        .btn-add-new .icon-circle {
            background-color: var(--gold-accent); color: var(--sidebar-bg);
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; transition: transform 0.3s ease;
        }
        .btn-add-new:hover {
            transform: translateY(-2px); box-shadow: 0 6px 16px rgba(30, 41, 59, 0.25);
            background-color: #ffffff; color: var(--sidebar-bg);
        }
        .btn-add-new:hover .icon-circle {
            transform: rotate(90deg); background-color: var(--sidebar-bg); color: var(--gold-accent);
        }

        /* ===== البانل والجدول ===== */
        .panel { 
            background-color: #ffffff; border: 1px solid var(--border-color); 
            border-radius: 12px; padding: 1.5rem; box-shadow: var(--shadow-sm); 
        }
        .table-responsive { overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; text-align: right; }
        .custom-table th { 
            color: var(--text-secondary); font-size: 0.85rem; 
            padding: 1rem 0.75rem; border-bottom: 2px solid var(--primary-bg); 
            white-space: nowrap;
        }
        .custom-table td { 
            padding: 1rem 0.75rem; border-bottom: 1px solid var(--primary-bg); 
            font-size: 0.95rem; color: var(--text-primary); vertical-align: middle;
        }
        .custom-table tbody tr:hover { background-color: rgba(244, 246, 249, 0.5); }

        .badge-jurisdiction {
            background-color: rgba(30, 41, 59, 0.05); color: var(--sidebar-bg);
            border: 1px solid rgba(30, 41, 59, 0.1); padding: 0.35rem 0.85rem;
            border-radius: 6px; font-size: 0.85rem; font-weight: 600; display: inline-block;
        }

        .btn-action { 
            display: inline-flex; align-items: center; justify-content: center; 
            width: 34px; height: 34px; border-radius: 8px; font-size: 0.9rem; 
            cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; 
            text-decoration: none;
        }
        .btn-action-edit { background: rgba(212, 175, 55, 0.1); color: #9a7b21; border-color: rgba(212, 175, 55, 0.2); }
        .btn-action-edit:hover { background: var(--gold-accent); color: #ffffff; }
        
        .btn-action-delete { background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2); }
        .btn-action-delete:hover { background: var(--danger-color); color: #ffffff; }

        .empty-state-container { text-align: center; padding: 4rem 1rem; }
        .empty-state-icon-wrapper { 
            width: 90px; height: 90px; background-color: rgba(30, 41, 59, 0.03); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.02); 
        }
        .empty-state-icon-wrapper i { font-size: 3.5rem; color: var(--border-color); }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .search-input-field {
            padding: 0.7rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            width: 100%;
            max-width: 350px;
            outline: none;
            transition: all 0.2s;
        }
        .search-input-field:focus {
            border-color: var(--gold-accent);
        }

        @media (max-width: 768px) {
            .courts-page-container { padding: 1rem; }
        }
    </style>

    <div class="courts-page-container">

        @if (session()->has('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="dashboard-header">
            <div>
                <h1 class="welcome-title">{{ __('إدارة المحاكم') }}</h1>
                <p class="date-text">{{ __('إعداد وتكوين المحاكم وجهات التقاضي في النظام') }}</p>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('ابحث عن محكمة أو جهة...') }}" class="search-input-field">

                @can('manage-courts')
                    <a href="{{ route('courts.create') }}" wire:navigate class="btn-add-new">
                        <span class="icon-circle">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>{{ __('إضافة محكمة جديدة') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <div class="panel">
            @if($courts->count() > 0)
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">{{ __('المعرف') }}</th>
                                <th style="width: 45%;">{{ __('اسم المحكمة') }}</th>
                                <th style="width: 30%;">{{ __('جهة التقاضي') }}</th>
                                <th style="width: 15%; text-align: center;">{{ __('إجراءات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courts as $court)
                                <tr wire:key="court-{{ $court->id }}">
                                    <td style="text-align: center; font-weight: 700; color: var(--sidebar-bg);">
                                        #{{ $court->id }}
                                    </td>

                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.6rem; font-weight: 700; color: var(--text-primary);">
                                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: rgba(212, 175, 55, 0.1); color: var(--gold-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                                <i class="fas fa-landmark"></i>
                                            </div>
                                            <span>{{ $court->name }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        @if($court->jurisdiction)
                                            <span class="badge-jurisdiction">
                                                {{ $court->jurisdiction->name }}
                                            </span>
                                        @else
                                            <span style="color: var(--text-secondary);">—</span>
                                        @endif
                                    </td>

                                    <td style="text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                            @can('manage-courts')
                                                <a href="{{ route('courts.edit', $court->id) }}" wire:navigate class="btn-action btn-action-edit" title="{{ __('تعديل المحكمة') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" wire:click="delete({{ $court->id }})" 
                                                    wire:confirm="{{ __('هل أنت متأكد من حذف هذه المحكمة نهائياً؟') }}"
                                                    class="btn-action btn-action-delete" title="{{ __('حذف المحكمة') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @else
                                                <span style="font-size: 0.8rem; color: var(--text-secondary);">{{ __('عرض فقط') }}</span>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state-container">
                    <div class="empty-state-icon-wrapper">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <h3 style="color: var(--text-primary); font-size: 1.4rem; margin-bottom: 0.5rem; font-weight: 800;">{{ __('لا توجد محاكم مسجلة') }}</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 2.5rem;">{{ __('لم يتم إضافة أي محاكم للنظام حتى الآن.') }}</p>
                    
                    @can('manage-courts')
                        <a href="{{ route('courts.create') }}" wire:navigate class="btn-add-new">
                            <span class="icon-circle">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>{{ __('إضافة المحكمة الأولى') }}</span>
                        </a>
                    @endcan
                </div>
            @endif
        </div>

    </div>
</div>
