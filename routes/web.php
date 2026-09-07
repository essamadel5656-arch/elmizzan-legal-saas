<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\JurisdictionController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LegalCaseController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;

// Livewire Full-Page SPA Components
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Notifications\NotificationIndex;
use App\Livewire\Settings\SettingsEdit;
use App\Livewire\Cases\CaseIndex;
use App\Livewire\Cases\CaseCreate;
use App\Livewire\Cases\CaseShow;
use App\Livewire\Cases\CaseEdit;
use App\Livewire\Appointments\AppointmentIndex;
use App\Livewire\Appointments\AppointmentCreate;
use App\Livewire\Appointments\AppointmentNotifications;
use App\Livewire\Clients\ClientIndex;
use App\Livewire\Clients\ClientCreate;
use App\Livewire\Clients\ClientShow;
use App\Livewire\Lawyers\LawyerIndex;
use App\Livewire\Lawyers\LawyerCreate;
use App\Livewire\Lawyers\LawyerShow;
use App\Livewire\Lawyers\LawyerEdit;
use App\Livewire\Courts\CourtIndex;
use App\Livewire\Courts\CourtCreate;
use App\Livewire\Courts\CourtEdit;
use App\Livewire\Jurisdictions\JurisdictionIndex;
use App\Livewire\Jurisdictions\JurisdictionCreate;
use App\Livewire\Jurisdictions\JurisdictionEdit;
use App\Livewire\Documents\DocumentIndex;
use App\Livewire\Contracts\ContractIndex;

// Dynamic PWA Manifest — public, no auth required
Route::get('/manifest.json', \App\Http\Controllers\PwaManifestController::class)
    ->name('pwa.manifest');

// ✅ التسجيل مغلق — الأدمن بس يضيف مستخدمين
Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {

    // ─── Dashboard (SPA) ────────────────────────────────────────────────────────
    Route::get('/home', DashboardIndex::class)->name('home');

    // ─── Contracts (SPA) ────────────────────────────────────────────────────────
    Route::get('/contracts', ContractIndex::class)->name('contracts.index');
    Route::get('/contracts/{type}', [ContractController::class, 'show'])->name('contracts.show');

    // ─── Chat (Persistent Widget & Streaming APIs) ──────────────────────────────
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/new-session', [ChatController::class, 'createSession'])->name('chat.create_session');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/session/{id}/messages', [ChatController::class, 'getSessionMessages'])->name('chat.session.messages');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::delete('/chat/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // ─── Appointments & Sessions (SPA) ──────────────────────────────────────────
    Route::get('appointments/notifications', AppointmentNotifications::class)->name('appointments.notifications');
    Route::get('appointments/create/{case}', AppointmentCreate::class)->name('appointments.create');
    Route::get('appointments', AppointmentIndex::class)->name('appointments.index');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // ─── Documents (SPA) ────────────────────────────────────────────────────────
    Route::get('document/index', DocumentIndex::class)->name('document.index');
    Route::get('document/add_documents/{case}', [DocumentController::class, 'create'])->name('document.add_documents');
    Route::post('document/store', [DocumentController::class, 'store'])->name('document.store');
    Route::get('document/{document}', [DocumentController::class, 'show'])->name('document.show');
    Route::get('document/{document}/edit', [DocumentController::class, 'edit'])->name('document.edit');
    Route::put('document/{document}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('document/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');

    // ─── Clients (SPA) ──────────────────────────────────────────────────────────
    Route::get('clients', ClientIndex::class)->name('clients.index');
    Route::get('clients/create', ClientCreate::class)->name('add-client');
    Route::post('clients/create', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}', ClientShow::class)->name('clients.show');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // ─── Jurisdictions (SPA) ────────────────────────────────────────────────────
    Route::get('jurisdictions', JurisdictionIndex::class)->name('jurisdictions.index');
    Route::get('jurisdictions/create', JurisdictionCreate::class)->name('jurisdictions.create');
    Route::get('jurisdictions/{jurisdiction}/edit', JurisdictionEdit::class)->name('jurisdictions.edit');
    Route::post('jurisdictions', [JurisdictionController::class, 'store'])->name('jurisdictions.store');
    Route::put('jurisdictions/{jurisdiction}', [JurisdictionController::class, 'update'])->name('jurisdictions.update');
    Route::delete('jurisdictions/{jurisdiction}', [JurisdictionController::class, 'destroy'])->name('jurisdictions.destroy');

    // ─── Courts (SPA) ───────────────────────────────────────────────────────────
    Route::get('courts', CourtIndex::class)->name('courts.index');
    Route::get('courts/create', CourtCreate::class)->name('courts.create');
    Route::get('courts/{court}/edit', CourtEdit::class)->name('courts.edit');
    Route::post('courts', [CourtController::class, 'store'])->name('courts.store');
    Route::put('courts/{court}', [CourtController::class, 'update'])->name('courts.update');
    Route::delete('courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');

    // ─── Cases (SPA) ────────────────────────────────────────────────────────────
    Route::get('cases', CaseIndex::class)->name('cases.index');
    Route::get('cases/create', CaseCreate::class)->name('cases.create');
    Route::get('cases/{case}', CaseShow::class)->name('cases.show');
    Route::get('cases/{case}/edit', CaseEdit::class)->name('cases.edit');
    Route::post('cases', [LegalCaseController::class, 'store'])->name('cases.store');
    Route::put('cases/{case}', [LegalCaseController::class, 'update'])->name('cases.update');
    Route::delete('cases/{case}', [LegalCaseController::class, 'destroy'])->name('cases.destroy');
    Route::get('/get-courts', [LegalCaseController::class, 'getCourts'])->name('api.get-courts');
    Route::get('/api/courts', [LegalCaseController::class, 'getCourts'])->name('api.courts');

    // ─── Lawyers (SPA) ──────────────────────────────────────────────────────────
    Route::get('lawyers', LawyerIndex::class)->name('lawyers.index');
    Route::middleware('admin')->group(function () {
        Route::get('lawyers/create', LawyerCreate::class)->name('lawyers.create');
        Route::post('lawyers', [LawyerController::class, 'store'])->name('lawyers.store');
        Route::delete('lawyers/{lawyer}', [LawyerController::class, 'destroy'])->name('lawyers.destroy');
    });
    Route::get('lawyers/{lawyer}', LawyerShow::class)->name('lawyers.show');
    Route::get('lawyers/{lawyer}/edit', LawyerEdit::class)->name('lawyers.edit');
    Route::put('lawyers/{lawyer}', [LawyerController::class, 'update'])->name('lawyers.update');

    // ─── Notifications (SPA) ────────────────────────────────────────────────────
    Route::get('/notifications', NotificationIndex::class)->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // ─── Workspace Messaging (SPA) ───────────────────────────────────────────
    Route::get('/workspace/chat', \App\Livewire\Messaging\WorkspaceChat::class)->name('workspace.chat');
    Route::get('/workspace/chat/{user}', \App\Livewire\Messaging\WorkspaceChat::class)->name('workspace.chat.user');

    // ─── System Settings (Admin only, SPA) ──────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::get('/settings', SettingsEdit::class)->name('settings.edit');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // ─── Expense Voucher (printable receipt) ─────────────────────────────────────
    Route::get('/expenses/{expense}/voucher', function (\App\Models\CaseExpense $expense) {
        abort_unless(auth()->user()?->isAdmin(), 403);
        return view('expenses.voucher', compact('expense'));
    })->name('expenses.voucher');

    // ─── Client Portal ────────────────────────────────────────────────────────────
    Route::middleware('auth')->prefix('client-portal')->group(function () {
        Route::get('/', \App\Livewire\ClientPortal\Dashboard::class)->name('client-portal.dashboard');
        Route::get('/pay/{case}', \App\Livewire\Payments\ClientPaymentCreate::class)->name('client-portal.pay');
        Route::get('/upload/{documentRequest}', \App\Livewire\ClientPortal\DocumentUpload::class)->name('client-portal.upload');
    });

});

// ─── Demo Access & Exit (Public) ──────────────────────────────────────────────
Route::get('/demo-access', \App\Livewire\Demo\DemoAccess::class)->name('demo.access');

Route::match(['get', 'post'], '/demo-exit', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->forget('is_demo');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('demo.exit');

// ─── Client Portal Activation ─────────────────────────────────────────────────
Route::get('/activate/{token}', function (string $token) {
    $user = \App\Models\User::where('activation_token', $token)
        ->where('activation_token_expires_at', '>', now())
        ->first();

    if (! $user) {
        abort(404, 'رابط التفعيل غير صالح أو منتهي الصلاحية.');
    }

    return view('auth.activate', compact('user', 'token'));
})->name('activate');

Route::post('/activate/{token}', function (string $token, \Illuminate\Http\Request $request) {
    $request->validate([
        'password'              => 'required|string|min:8|confirmed',
        'password_confirmation' => 'required|string',
    ]);

    $user = \App\Models\User::where('activation_token', $token)
        ->where('activation_token_expires_at', '>', now())
        ->first();

    if (! $user) {
        abort(404, 'رابط التفعيل غير صالح أو منتهي الصلاحية.');
    }

    $user->update([
        'password'                    => bcrypt($request->password),
        'activation_token'            => null,
        'activation_token_expires_at' => null,
        'email_verified_at'           => now(),
    ]);

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('client-portal.dashboard')
        ->with('success', 'تم تفعيل حسابك بنجاح! مرحباً بك في بوابة الموكل.');
})->name('activate.set-password');