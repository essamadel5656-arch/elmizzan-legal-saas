<div class="toast-container">
    @if(session('success'))
        <div class="toast toast-success show">
            <div class="toast-header">
                <strong>تم بنجاح</strong>
                <span class="close-btn" onclick="this.parentElement.parentElement.remove()">×</span>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast toast-error show">
            <div class="toast-header">
                <strong>خطأ</strong>
                <span class="close-btn" onclick="this.parentElement.parentElement.remove()">×</span>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @foreach ($errors->all() as $error)
        <div class="toast toast-error show">
            <div class="toast-header">
                <strong>خطأ</strong>
                <span class="close-btn" onclick="this.parentElement.parentElement.remove()">×</span>
            </div>
            <div class="toast-body">
                {{ $error }}
            </div>
        </div>
    @endforeach
</div>