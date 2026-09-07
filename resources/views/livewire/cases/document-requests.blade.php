<div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash Message --}}
    @if(session()->has('doc_success'))
        <div class="mb-4 p-3 flex items-center gap-2 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl font-bold text-sm backdrop-blur-sm">
            <i class="fas fa-check-circle"></i> {{ __(session('doc_success')) }}
        </div>
    @endif

    <div class="flex flex-wrap justify-between items-center mb-5 gap-4">
        <div class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-amber-500">
            <i class="fas fa-file-invoice text-amber-500"></i> {{ __('Document Requests') }}
            @php $pendingCount = $requests->where('status', 'pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 px-2.5 py-1 rounded-full text-[10px] font-bold ms-2 animate-pulse flex items-center gap-1.5">
                    <i class="fas fa-exclamation-circle"></i> {{ $pendingCount }} {{ __('Pending Upload') }}
                </span>
            @endif
        </div>

        @if(!auth()->user()->isClient())
            <button type="button" wire:click="$toggle('showForm')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 dark:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                @if($showForm)
                    <i class="fas fa-times"></i> {{ __('Close Form') }}
                @else
                    <i class="fas fa-file-upload"></i> {{ __('Request New Document') }}
                @endif
            </button>
        @endif
    </div>

    {{-- Create Request Form (for lawyers/admin) --}}
    @if($showForm && !auth()->user()->isClient())
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-5 md:p-6 mb-6 transition-all" wire:key="doc-form">
            <form wire:submit="createRequest" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Requested Document Title') }}</label>
                        <input type="text" wire:model="title" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5" placeholder="{{ __('e.g. ID Card Copy, Ownership Contract...') }}">
                        @error('title') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Client') }}</label>
                        <select wire:model="client_id" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5">
                            <option value="">{{ __('-- Select Client --') }}</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-semibold text-xs text-slate-900 dark:text-slate-100 mb-1.5">{{ __('Additional Description (Optional)') }}</label>
                        <textarea wire:model="description" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2.5 resize-y" placeholder="{{ __('Extra details about the requested document...') }}"></textarea>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 dark:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane"></i> {{ __('Send Request') }}
                    </button>
                    <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-sm font-bold transition-all">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Requests List --}}
    @if($requests->isEmpty())
        <div class="text-center text-sm font-bold italic text-slate-400 dark:text-slate-500 py-8 bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl">
            <i class="fas fa-folder-open mb-2 text-2xl opacity-50 block"></i>
            {{ __('No document requests for this case yet.') }}
        </div>
    @else
        <div class="space-y-3">
            @foreach($requests as $req)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white dark:bg-slate-800 border {{ $req->isPending() ? 'border-red-200 bg-red-50/30 dark:border-red-900/50 dark:bg-red-900/10 hover:border-red-300 dark:hover:border-red-800' : 'border-slate-200 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-500' }} rounded-xl transition-all hover:shadow-sm gap-4" wire:key="req-{{ $req->id }}">
                    
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-sm text-slate-900 dark:text-slate-100 mb-1">
                            {{ $req->title }}
                        </div>
                        @if($req->description)
                            <div class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ $req->description }}</div>
                        @endif
                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-500 flex-wrap">
                            <span class="bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded-md"><i class="fas fa-user text-slate-400"></i> {{ __('Client:') }} {{ $req->client?->name ?? __('Unknown') }}</span>
                            <span class="bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded-md"><i class="fas fa-user-tie text-slate-400"></i> {{ __('Requested By:') }} {{ $req->requestedBy?->name ?? __('Unknown') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 flex-wrap">
                        @if($req->isPending())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50 whitespace-nowrap">
                                <i class="fas fa-hourglass-half"></i> {{ __('Pending Upload') }}
                            </span>
                        @elseif($req->isUploaded())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50 whitespace-nowrap">
                                <i class="fas fa-paperclip"></i> {{ __('Uploaded') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50 whitespace-nowrap">
                                <i class="fas fa-check-circle"></i> {{ __('Approved') }}
                            </span>
                        @endif

                        @if($req->isUploaded() && !auth()->user()->isClient())
                            <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-2 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white border border-amber-200 dark:border-amber-800/50 rounded-lg text-[10px] font-bold transition-colors whitespace-nowrap">
                                <i class="fas fa-external-link-alt"></i> {{ __('Open') }}
                            </a>
                            <button wire:click="approveDocument({{ $req->id }})" class="inline-flex items-center justify-center gap-1.5 px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 border border-transparent rounded-lg text-[10px] font-bold transition-colors whitespace-nowrap">
                                <i class="fas fa-check"></i> {{ __('Approve') }}
                            </button>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <button wire:click="deleteRequest({{ $req->id }})" class="text-slate-400 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 transition-colors p-1" title="{{ __('Delete') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
