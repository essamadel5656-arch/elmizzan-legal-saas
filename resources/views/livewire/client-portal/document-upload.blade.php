<div>
    <style>
        .upload-container { max-width:600px; margin:0 auto; }
        .upload-card { background:var(--card-bg,#fff); border:1px solid var(--border-color); border-radius:16px; padding:2rem; box-shadow:0 4px 16px rgba(0,0,0,.06); }
        .upload-title { font-size:1.3rem; font-weight:800; color:var(--text-primary); margin-bottom:.4rem; }
        .upload-subtitle { font-size:.9rem; color:var(--text-secondary); margin-bottom:1.5rem; }

        .dropzone {
            border:2.5px dashed rgba(212,175,55,.5); border-radius:12px;
            padding:2.5rem 2rem; text-align:center; cursor:pointer;
            transition:.3s; background:rgba(212,175,55,.03);
            position:relative;
        }
        .dropzone:hover, .dropzone.dragover {
            border-color:var(--gold-accent,#d4af37);
            background:rgba(212,175,55,.07);
        }
        .dropzone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .dropzone-icon { font-size:3rem; margin-bottom:.75rem; display:block; }
        .dropzone-text { font-size:.95rem; font-weight:700; color:var(--text-primary); margin-bottom:.3rem; }
        .dropzone-hint { font-size:.82rem; color:var(--text-secondary); }

        .file-preview-box {
            display:flex; align-items:center; gap:.85rem;
            padding:.85rem 1rem; background:rgba(34,197,94,.06);
            border:1px solid rgba(34,197,94,.25); border-radius:10px; margin-top:1rem;
        }
        .file-preview-icon { font-size:1.6rem; }
        .file-preview-name { font-size:.9rem; font-weight:700; color:var(--text-primary); }
        .file-preview-size { font-size:.78rem; color:var(--text-secondary); }

        .doc-info-box { background:var(--primary-bg,#f1f5f9); border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem; }
        .doc-info-label { font-size:.8rem; color:var(--text-secondary); font-weight:600; margin-bottom:.2rem; }
        .doc-info-value { font-size:.95rem; font-weight:700; color:var(--text-primary); }
    </style>

    <div class="upload-container">
        {{-- Flash --}}
        @if(session()->has('upload_success'))
            <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#15803d;border-radius:9px;padding:.85rem 1.2rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
                ✅ {{ session('upload_success') }}
            </div>
        @endif

        <div class="upload-card">
            <h1 class="upload-title">📤 رفع مستند مطلوب</h1>
            <p class="upload-subtitle">قم برفع المستند المطلوب منك من قِبَل فريق المكتب القانوني</p>

            {{-- Document Request Info --}}
            <div class="doc-info-box">
                <div class="doc-info-label">المستند المطلوب</div>
                <div class="doc-info-value">{{ $documentRequest->title }}</div>
                @if($documentRequest->description)
                    <div style="font-size:.85rem;color:var(--text-secondary);margin-top:.35rem;">{{ $documentRequest->description }}</div>
                @endif
                <div style="font-size:.78rem;color:var(--text-secondary);margin-top:.4rem;">القضية: {{ $documentRequest->case?->case_number }}</div>
            </div>

            <form wire:submit="upload">
                {{-- Drag & Drop Zone --}}
                <div class="dropzone" id="drop-zone"
                     x-data="{ isDragging: false }"
                     @dragover.prevent="isDragging = true; $el.classList.add('dragover')"
                     @dragleave.prevent="isDragging = false; $el.classList.remove('dragover')"
                     @drop.prevent="isDragging = false; $el.classList.remove('dragover')">
                    <input type="file" wire:model="file" accept=".pdf,.doc,.docx,.xlsx,.xls,.png,.jpg,.jpeg,.webp">
                    <span class="dropzone-icon">📂</span>
                    <div class="dropzone-text">اسحب وأفلت الملف هنا، أو اضغط للاختيار</div>
                    <div class="dropzone-hint">الصيغ المقبولة: PDF · DOCX · XLSX · PNG · JPG · WebP — الحجم الأقصى: 10MB</div>
                </div>

                {{-- File Preview --}}
                @if($file)
                    <div class="file-preview-box" wire:key="file-preview">
                        <span class="file-preview-icon">📄</span>
                        <div>
                            <div class="file-preview-name">{{ $file->getClientOriginalName() }}</div>
                            <div class="file-preview-size">{{ round($file->getSize() / 1024) }} KB</div>
                        </div>
                    </div>
                @endif

                @error('file')
                    <div style="color:#dc2626;font-size:.85rem;margin-top:.75rem;">⚠ {{ $message }}</div>
                @enderror

                <button type="submit" wire:loading.attr="disabled"
                    style="margin-top:1.5rem;width:100%;background:linear-gradient(135deg,#1e293b,#0f172a);color:var(--gold-accent,#d4af37);border:none;padding:.85rem;border-radius:10px;font-size:1rem;font-weight:800;cursor:pointer;font-family:inherit;transition:.2s;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <span wire:loading.remove>📤 رفع المستند الآن</span>
                    <span wire:loading>⏳ جاري الرفع...</span>
                </button>

                <a href="{{ route('client-portal.dashboard') }}" wire:navigate
                   style="display:block;text-align:center;margin-top:.85rem;color:var(--text-secondary);font-size:.85rem;text-decoration:none;">
                    ← العودة للبوابة
                </a>
            </form>
        </div>
    </div>
</div>
