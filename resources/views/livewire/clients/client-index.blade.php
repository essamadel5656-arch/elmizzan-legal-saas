<div class="p-4 md:p-8 max-w-7xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="mb-6 p-4 flex items-center gap-3 bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl backdrop-blur-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-bold">{{ __(session('success')) }}</span>
        </div>
    @endif
    
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('Client Management') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('View and track data and locations of all clients') }}</p>
        </div>
        
        <a href="{{ route('add-client') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white pl-6 pr-2 py-2 rtl:pr-6 rtl:pl-2 rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-lg shadow-sm border border-slate-800 dark:border-amber-500 group">
            <span>{{ __('Add New Client') }}</span>
            <span class="w-8 h-8 flex items-center justify-center bg-amber-500 dark:bg-slate-900 text-slate-900 dark:text-amber-500 rounded-full transition-transform group-hover:rotate-90">
                <i class="fas fa-plus"></i>
            </span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 mb-6 flex flex-wrap items-center gap-4 transition-all hover:border-amber-500/50">
        <div class="flex-1 min-w-[300px] flex items-center gap-3">
            <div class="relative flex-1 flex items-center">
                <i class="fas fa-search absolute rtl:right-4 ltr:left-4 text-slate-400 dark:text-slate-500"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by name, phone, national ID or address...') }}" autocomplete="off" class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 rtl:pr-10 ltr:pl-10">
            </div>
            
            @if(!empty($search))
                <button type="button" wire:click="clearSearch" class="px-4 py-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-bold transition-all hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </button>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">
            @if(!empty($search))
                {{ __('Found') }} <strong class="text-slate-900 dark:text-amber-500 text-base mx-1">{{ $clients->count() }}</strong> {{ __('clients matching the search') }} <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-md font-bold mx-1">"{{ $search }}"</span>
            @else
                {{ __('Total registered clients:') }} <strong class="text-slate-900 dark:text-amber-500 text-base mx-1">{{ $clients->count() }}</strong> {{ __('client(s)') }}
            @endif
        </div>
    </div>

    @if($clients->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($clients as $client)
                <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 flex flex-col gap-4 transition-all hover:-translate-y-1 hover:shadow-lg hover:border-amber-500 relative overflow-hidden group" wire:key="client-{{ $client->id }}">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left rtl:origin-right"></div>
                    
                    <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-2 border-amber-200 dark:border-amber-800/50 flex items-center justify-center font-black text-xl flex-shrink-0 shadow-sm">
                            {{ mb_substr($client->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 truncate w-full" title="{{ $client->name }}">{{ $client->name }}</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ __('System ID:') }} #{{ $client->id }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 bg-slate-50/50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50">
                        @if($client->phone)
                            <div class="flex items-start gap-3 text-sm text-slate-700 dark:text-slate-300">
                                <span class="text-amber-500 w-5 text-center mt-0.5"><i class="fas fa-phone-alt"></i></span>
                                <a href="tel:{{ $client->phone }}" class="font-semibold hover:text-amber-600 dark:hover:text-amber-400 transition-colors dir-ltr flex-1 truncate">{{ $client->phone }}</a>
                            </div>
                        @endif

                        @if($client->email)
                            <div class="flex items-start gap-3 text-sm text-slate-700 dark:text-slate-300">
                                <span class="text-amber-500 w-5 text-center mt-0.5"><i class="fas fa-envelope"></i></span>
                                <a href="mailto:{{ $client->email }}" class="font-semibold hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex-1 truncate" title="{{ $client->email }}">{{ $client->email }}</a>
                            </div>
                        @endif

                        @if($client->nid)
                            <div class="flex items-start gap-3 text-sm text-slate-700 dark:text-slate-300">
                                <span class="text-amber-500 w-5 text-center mt-0.5"><i class="fas fa-id-card"></i></span>
                                <span class="font-semibold flex-1 truncate">{{ $client->nid }}</span>
                            </div>
                        @endif

                        @if($client->address)
                            <div class="flex items-start gap-3 text-sm text-slate-700 dark:text-slate-300">
                                <span class="text-amber-500 w-5 text-center mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="font-semibold flex-1 line-clamp-2" title="{{ $client->address }}">{{ Str::limit($client->address, 45) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto pt-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('clients.show', $client->id) }}" wire:navigate class="flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-900 hover:text-white dark:hover:bg-slate-700 rounded-lg text-xs font-bold transition-colors">
                            <i class="fas fa-folder-open"></i> {{ __('View File') }}
                        </a>
                        
                        @can('delete', $client)
                            <button type="button" wire:click="delete({{ $client->id }})" 
                                wire:confirm="{{ __('Are you sure you want to permanently delete this client from the system? This action cannot be undone.') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-lg text-xs font-bold transition-colors">
                                <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                            </button>
                        @else
                            <span class="flex items-center justify-center px-4 py-2 text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-800 cursor-not-allowed">
                                {{ __('Cannot Delete') }}
                            </span>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700">
                <i class="fas {{ !empty($search) ? 'fa-search-minus' : 'fa-users-slash' }} text-4xl text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-3">
                {{ !empty($search) ? __('No matching results') : __('No registered clients') }}
            </h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">
                {{ !empty($search) ? __('We couldn\'t find any records matching your search. Please check your keywords or clear the filter and try again.') : __('The dashboard is currently empty. Start by adding the first client to the system so you can link them to cases and appointments.') }}
            </p>
            
            @if(empty($search))
                <a href="{{ route('add-client') }}" wire:navigate class="inline-flex items-center gap-3 bg-slate-900 dark:bg-amber-600 text-white pl-6 pr-2 py-2 rtl:pr-6 rtl:pl-2 rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-lg shadow-sm border border-slate-800 dark:border-amber-500 group">
                    <span>{{ __('Add First Client') }}</span>
                    <span class="w-8 h-8 flex items-center justify-center bg-amber-500 dark:bg-slate-900 text-slate-900 dark:text-amber-500 rounded-full transition-transform group-hover:rotate-90">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
            @else
                <button type="button" wire:click="clearSearch" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl font-bold transition-all shadow-sm">
                    <i class="fas fa-redo"></i> {{ __('Clear Search and Return') }}
                </button>
            @endif
        </div>
    @endif

</div>
