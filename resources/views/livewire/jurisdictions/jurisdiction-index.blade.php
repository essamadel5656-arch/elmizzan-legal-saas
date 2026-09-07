<div>
    <div>
        @if (session()->has('success'))
            <div class="badge-item badge-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="panel-header">
            <div>
                <h1 class="panel-title">{{ __('جهات التقاضي والمحاكم التابعة') }}</h1>
                <p class="text-muted">{{ __('إدارة أنواع جهات القضاء والمحاكم المسجلة تحت كل جهة') }}</p>
            </div>
            
            <div class="table-actions">
                @can('manage-courts')
                    <a href="{{ route('courts.create') }}" wire:navigate class="btn-add-new">
                        <span class="icon-circle">
                            <i class="fas fa-landmark"></i>
                        </span>
                        <span>{{ __('إضافة محكمة') }}</span>
                    </a>

                    <a href="{{ route('jurisdictions.create') }}" wire:navigate class="btn-add-new">
                        <span class="icon-circle">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>{{ __('إضافة جهة تقاضي') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <div class="panel">
            @if($jurisdictions->count() > 0)
                <div>
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>{{ __('المعرف') }}</th>
                                <th>{{ __('نوع جهة التقاضي') }}</th>
                                <th>{{ __('المحاكم التابعة للجهة') }}</th>
                                <th>{{ __('إجراءات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurisdictions as $jurisdiction)
                                <tr wire:key="jurisdiction-{{ $jurisdiction->id }}">
                                    <td>
                                        #{{ $jurisdiction->id }}
                                    </td>

                                    <td>
                                        <div class="table-actions">
                                            <div class="badge-item">
                                                <i class="fas fa-balance-scale"></i>
                                            </div>
                                            <div>
                                                <span class="panel-title">{{ $jurisdiction->name }}</span>
                                                <br>
                                                <span class="text-muted">{{ $jurisdiction->courts->count() }} {{ __('محكمة مسجلة') }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($jurisdiction->courts->count() > 0)
                                            <div>
                                                @foreach($jurisdiction->courts as $court)
                                                    <span class="badge-item">
                                                        <i class="fas fa-landmark"></i>
                                                        {{ $court->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('لا توجد محاكم مسجلة تحت هذه الجهة حالياً') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="table-actions">
                                            @can('manage-courts')
                                                <a href="{{ route('jurisdictions.edit', $jurisdiction->id) }}" wire:navigate class="btn-action btn-action-edit" title="{{ __('تعديل الجهة') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" wire:click="delete({{ $jurisdiction->id }})"
                                                    wire:confirm="{{ __('هل أنت متأكد من حذف هذه الجهة؟ سيؤدي ذلك لمشاكل بالقضايا المرتبطة بها إن وجدت.') }}"
                                                    class="btn-action btn-action-delete" title="{{ __('حذف الجهة') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">{{ __('عرض فقط') }}</span>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>
                    <div class="badge-item">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <br><br>
                    <h3 class="panel-title">{{ __('لا توجد جهات تقاضي') }}</h3>
                    <p class="text-muted">{{ __('ابدأ بتسجيل جهات التقاضي المختلفة لتنظيم المحاكم والقضايا.') }}</p>
                    <br>
                    @can('manage-courts')
                        <a href="{{ route('jurisdictions.create') }}" wire:navigate class="btn-add-new">
                            <span class="icon-circle">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>{{ __('إضافة أول جهة تقاضي') }}</span>
                        </a>
                    @endcan
                </div>
            @endif
        </div>

    </div>
</div>