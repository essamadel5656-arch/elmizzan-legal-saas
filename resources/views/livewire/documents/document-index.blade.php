<div class="p-4 md:p-8 max-w-7xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-folder-open text-amber-500"></i> {{ __('Document Management') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('View and track all attachments and documents related to cases') }}
            </p>
        </div>
        
        <a href="{{ route('cases.index') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white pl-6 pr-2 py-2 rtl:pr-6 rtl:pl-2 rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-lg shadow-sm border border-slate-800 dark:border-amber-500 group" title="{{ __('Go to cases page to add document') }}">
            <div class="flex flex-col items-start leading-tight">
                <span>{{ __('Add Document') }}</span>
                <span class="text-[10px] font-medium opacity-80">{{ __('(From Case Page)') }}</span>
            </div>
            <span class="w-8 h-8 flex items-center justify-center bg-amber-500 dark:bg-slate-900 text-slate-900 dark:text-amber-500 rounded-full transition-transform group-hover:rotate-90">
                <i class="fas fa-file-upload"></i>
            </span>
        </a>
    </div>

    {{-- Panel --}}
    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 mb-6 transition-all hover:border-amber-500/50">
        
        {{-- Search Bar --}}
        <div class="mb-6 max-w-lg">
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute rtl:right-4 ltr:left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 pointer-events-none"></i>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="{{ __('Search by document title or case number...') }}" 
                        class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 rtl:pr-11 ltr:pl-11"
                    >
                </div>

                @if($search)
                    <button type="button" wire:click="$set('search', '')" class="px-4 py-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold transition-all hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-times"></i> {{ __('Clear') }}
                    </button>
                @endif
            </div>

            @if($search)
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 font-semibold flex items-center gap-2">
                    <i class="fas fa-filter text-amber-500"></i>
                    {{ __('Live search results for:') }} <strong class="text-slate-900 dark:text-slate-100">"{{ $search }}"</strong>
                    <span class="text-slate-400 dark:text-slate-500 font-medium">— {{ $documents->count() }} {{ __('result(s)') }}</span>
                </p>
            @endif
        </div>

        {{-- Documents Table --}}
        @if($documents->count() > 0)
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-sm text-start">
                    <thead class="bg-slate-100/80 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[8%]">ID</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[25%]">{{ __('Document Title') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[20%]">{{ __('Case Number') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[20%]">{{ __('Type') }}</th>
                            <th class="px-6 py-4 rtl:text-right ltr:text-left w-[15%]">{{ __('Date Added') }}</th>
                            <th class="px-6 py-4 text-center w-[12%]">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group" wire:key="doc-{{ $doc->id }}">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">#{{ $doc->id }}</td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 font-bold text-slate-900 dark:text-slate-100">
                                        <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                        <span class="truncate w-full max-w-[200px]" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($doc->case)
                                        <a href="{{ route('cases.show', $doc->case->id) }}" wire:navigate class="inline-flex items-center gap-1.5 font-bold text-slate-900 dark:text-amber-500 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                            # {{ $doc->case->case_number }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 italic font-semibold">—</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-lg text-xs font-bold whitespace-nowrap">
                                        @if($doc->type === 'contract')
                                            <i class="fas fa-file-contract"></i> {{ __('Contract') }}
                                        @elseif($doc->type === 'report')
                                            <i class="fas fa-file-alt"></i> {{ __('Report') }}
                                        @elseif($doc->type === 'attachment')
                                            <i class="fas fa-paperclip"></i> {{ __('Attachment') }}
                                        @else
                                            {{ __($doc->type ?? '—') }}
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-600 dark:text-slate-400 dir-ltr text-end ltr:text-left">
                                        {{ $doc->created_at ? $doc->created_at->format('Y-m-d') : '—' }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('document.show', $doc->id) }}" wire:navigate class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-900 hover:text-white dark:hover:bg-slate-700 rounded-lg text-sm font-bold transition-colors" title="{{ __('View File') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('document.edit', $doc->id) }}" wire:navigate class="w-8 h-8 flex items-center justify-center bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white border border-amber-200 dark:border-amber-800/50 rounded-lg text-sm font-bold transition-colors" title="{{ __('Edit Data') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" wire:click="delete({{ $doc->id }})"
                                            wire:confirm="{{ __('Are you sure you want to permanently delete this document?') }}"
                                            class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-lg text-sm font-bold transition-colors" title="{{ __('Delete Document') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                    <i class="fas fa-file-excel text-4xl text-slate-300 dark:text-slate-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                    {{ __('No documents registered') }}
                </h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed text-sm">
                    {{ __('No documents have been uploaded to the system yet. Documents are added directly from within each case profile.') }}
                </p>
                
                <a href="{{ route('cases.index') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white pl-6 pr-2 py-2 rtl:pr-6 rtl:pl-2 rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-lg shadow-sm border border-slate-800 dark:border-amber-500 group">
                    <span>{{ __('Go to Cases Page') }}</span>
                    <span class="w-8 h-8 flex items-center justify-center bg-amber-500 dark:bg-slate-900 text-slate-900 dark:text-amber-500 rounded-full transition-transform group-hover:rotate-90">
                        <i class="fas fa-folder-open"></i>
                    </span>
                </a>
            </div>
        @endif
    </div>

</div>
