<div>
    <style>
        /* ===== الحاوية والترويسة الموحدة ===== */
        .library-page-container { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        
        .dashboard-header { 
            display: flex; justify-content: space-between; align-items: flex-start; 
            margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
        }
        .header-info h1 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem;}
        .header-info p { color: var(--text-secondary); font-size: 1rem; margin: 0; font-weight: 500;}

        /* ===== شبكة العقود ===== */
        .contracts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        /* ===== تصميم كارت العقد ===== */
        .contract-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .contract-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(30, 41, 59, 0.02));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .contract-card:hover {
            border-color: var(--gold-accent);
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(212, 175, 55, 0.12);
        }

        .contract-card:hover::after {
            opacity: 1;
        }

        .contract-icon {
            width: 55px; height: 55px;
            background: rgba(30, 41, 59, 0.04);
            color: var(--sidebar-bg);
            border-radius: 12px;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.6rem; flex-shrink: 0;
            transition: all 0.3s ease;
            border: 1px solid rgba(30, 41, 59, 0.05);
        }

        .contract-card:hover .contract-icon {
            background: var(--sidebar-bg);
            color: var(--gold-accent);
            transform: scale(1.1) rotate(-5deg);
            border-color: var(--sidebar-bg);
            box-shadow: 0 4px 10px rgba(30, 41, 59, 0.2);
        }

        .contract-details { flex: 1; }
        
        .contract-name {
            font-size: 1.15rem; font-weight: 800; color: var(--text-primary); 
            margin: 0 0 0.6rem 0; line-height: 1.4; transition: color 0.3s;
        }

        .contract-card:hover .contract-name {
            color: var(--sidebar-bg);
        }

        .contract-action {
            font-size: 0.9rem; color: var(--text-secondary); font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s;
        }

        .contract-card:hover .contract-action {
            color: var(--gold-accent); gap: 0.8rem;
        }

        .search-box-lib {
            padding: 0.75rem 1.2rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #ffffff;
            color: var(--text-primary);
            font-size: 0.95rem;
            width: 100%;
            max-width: 350px;
            outline: none;
            transition: all 0.2s;
        }
        .search-box-lib:focus {
            border-color: var(--gold-accent);
        }

        @media (max-width: 768px) {
            .library-page-container { padding: 1rem; }
            .dashboard-header { flex-direction: column; align-items: stretch; gap: 1rem; }
            .search-box-lib { max-width: 100%; }
        }
    </style>

    <div class="library-page-container">
        
        <div class="dashboard-header">
            <div class="header-info">
                <h1>
                    <i class="fas fa-book-bookmark" style="color: var(--gold-accent);"></i> 
                    المكتبة القانونية والصيغ النموذجية
                </h1>
                <p>نماذج العقود، الدعاوى القضائية، والصيغ القانونية المعتمدة والجاهزة للطباعة والتعديل</p>
            </div>

            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث في صيغ العقود..." class="search-box-lib">
        </div>

        <div class="contracts-grid">
            @forelse($contracts as $type => $title)
                <a href="{{ route('contracts.show', $type) }}" wire:navigate class="contract-card">
                    <div class="contract-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    
                    <div class="contract-details">
                        <h3 class="contract-name">{{ $title }}</h3>
                        <div class="contract-action">
                            <span>عرض النموذج والطباعة</span>
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #fff; border-radius: 12px; border: 1px solid var(--border-color);">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--text-primary); font-weight: 800;">لا توجد نتائج مطابقة لـ "{{ $search }}"</h3>
                    <p style="color: var(--text-secondary); margin-top: 0.5rem;">جرب كتابة اسم عقد آخر أو امسح البحث.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
