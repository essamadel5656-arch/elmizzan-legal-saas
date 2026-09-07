<div class="p-4 md:p-8 max-w-4xl mx-auto" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                <i class="fas fa-user-edit text-amber-500"></i> {{ __('Edit Lawyer Data') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Update profile:') }} <strong class="text-slate-700 dark:text-slate-300">{{ $lawyer->name }}</strong>
            </p>
        </div>
        <a href="{{ route('lawyers.show', $lawyer->id) }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-2.5 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-500 rounded-xl transition-colors font-bold whitespace-nowrap">
            <i class="fas fa-arrow-right rtl:rotate-180"></i> {{ __('Back to Profile') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-6 bg-red-50/80 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl backdrop-blur-sm">
            <strong class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i> {{ __('Please review the following errors to save:') }}
            </strong>
            <ul class="list-disc mx-5 text-red-600 dark:text-red-400 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" novalidate class="space-y-6">

        {{-- Basic & Contact Data --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-id-card text-amber-500"></i> {{ __('Basic and Contact Data') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Lawyer Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @error('name') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Mobile Number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" wire:model="phone" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" required>
                    @error('phone') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Email Address') }} <span class="text-red-500">*</span>
                    </label>
                    @if(auth()->user()->role === 'admin')
                        <input type="email" wire:model="email" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" required>
                    @else
                        <input type="email" value="{{ $email }}" class="w-full bg-slate-100 dark:bg-slate-800/50 border border-slate-300 border-dashed dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left cursor-not-allowed" readonly>
                        <div class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-lock text-amber-500"></i> {{ __('Email edit is restricted to administrators') }}
                        </div>
                    @endif
                    @error('email') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Address') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="address" rows="2" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y" required></textarea>
                    @error('address') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Professional Data --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-briefcase text-amber-500"></i> {{ __('Professional Data') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Specialization') }} <span class="text-red-500">*</span>
                    </label>
                    @if(auth()->user()->role === 'admin')
                        <input type="text" wire:model="specialization" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @else
                        <input type="text" value="{{ $specialization }}" class="w-full bg-slate-100 dark:bg-slate-800/50 border border-slate-300 border-dashed dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl transition-all text-sm px-4 py-3 cursor-not-allowed" readonly>
                        <div class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-lock text-amber-500"></i> {{ __('Specialization edit is restricted to administrators') }}
                        </div>
                    @endif
                    @error('specialization') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Registration Number') }} <span class="text-red-500">*</span>
                    </label>
                    @if(auth()->user()->role === 'admin')
                        <input type="text" wire:model="license_number" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                    @else
                        <input type="text" value="{{ $license_number }}" class="w-full bg-slate-100 dark:bg-slate-800/50 border border-slate-300 border-dashed dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl transition-all text-sm px-4 py-3 cursor-not-allowed" readonly>
                        <div class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-lock text-amber-500"></i> {{ __('Registration Number edit is restricted to administrators') }}
                        </div>
                    @endif
                    @error('license_number') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Degree') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="degree" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3" required>
                        <option value="{{ __('Cassation') }}">{{ __('Cassation') }}</option>
                        <option value="{{ __('Appeal') }}">{{ __('Appeal') }}</option>
                        <option value="{{ __('Primary') }}">{{ __('Primary') }}</option>
                        <option value="{{ __('General Table') }}">{{ __('General Table') }}</option>
                    </select>
                    @error('degree') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Short Bio') }}
                    </label>
                    <textarea wire:model="bio" rows="3" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 resize-y"></textarea>
                    @error('bio') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Admin Password Update --}}
        @if(auth()->user()->role === 'admin')
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
                <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                    <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                        <i class="fas fa-key text-amber-500"></i> {{ __('Set New Password (Optional)') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                            {{ __('New Password') }}
                        </label>
                        <input type="password" wire:model="password" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" placeholder="{{ __('Leave empty if you do not want to change it') }}">
                        @error('password') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                        <input type="password" wire:model="password_confirmation" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-4 py-3 dir-ltr text-end ltr:text-left" placeholder="{{ __('Re-enter password') }}">
                        @error('password_confirmation') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                    </div>
                </div>
            </div>
        @endif

        {{-- Attachments & Images --}}
        <div class="bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-none backdrop-blur-sm rounded-2xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-slate-100 dark:border-slate-800">
                <div class="text-lg font-bold text-slate-900 dark:text-amber-500 flex items-center gap-3">
                    <i class="fas fa-images text-amber-500"></i> {{ __('Attachments and Images') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Bar ID Image') }}
                    </label>
                    <input type="file" wire:model="bar_card_image" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300 cursor-pointer" accept="image/*">
                    @if($lawyer->bar_card_image)
                        <div class="mt-3 inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50 px-3 py-1.5 rounded-lg text-xs font-bold">
                            <i class="fas fa-check-circle"></i> {{ __('Currently Uploaded:') }} 
                            <a href="{{ asset('storage/' . $lawyer->bar_card_image) }}" target="_blank" class="underline hover:text-green-800 dark:hover:text-green-300">{{ __('Preview') }}</a>
                        </div>
                    @endif
                    @error('bar_card_image') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('Profile Image') }}
                    </label>
                    <input type="file" wire:model="profile_image" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300 cursor-pointer" accept="image/*">
                    @if($lawyer->profile_image)
                        <div class="mt-3 inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50 px-3 py-1.5 rounded-lg text-xs font-bold">
                            <i class="fas fa-check-circle"></i> {{ __('Currently Uploaded:') }} 
                            <a href="{{ asset('storage/' . $lawyer->profile_image) }}" target="_blank" class="underline hover:text-green-800 dark:hover:text-green-300">{{ __('Preview') }}</a>
                        </div>
                    @endif
                    @error('profile_image') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-sm text-slate-900 dark:text-slate-100 mb-2">
                        {{ __('National ID Image') }}
                    </label>
                    <input type="file" wire:model="national_id_image" class="w-full bg-white dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm px-3 py-2 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300 cursor-pointer" accept="image/*">
                    @if($lawyer->national_id_image)
                        <div class="mt-3 inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50 px-3 py-1.5 rounded-lg text-xs font-bold">
                            <i class="fas fa-check-circle"></i> {{ __('Currently Uploaded:') }} 
                            <a href="{{ asset('storage/' . $lawyer->national_id_image) }}" target="_blank" class="underline hover:text-green-800 dark:hover:text-green-300">{{ __('Preview') }}</a>
                        </div>
                    @endif
                    @error('national_id_image') <div class="text-xs text-red-500 mt-1 font-semibold">{{ __($message) }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('lawyers.show', $lawyer->id) }}" wire:navigate class="px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all font-bold">
                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-amber-600 hover:bg-slate-800 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-amber-600/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-amber-500/40 disabled:opacity-70 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
                <span wire:loading.remove wire:target="save">{{ __('Save Changes') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
        </div>

    </form>
</div>
