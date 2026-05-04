<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\{
    ContController, AdminController, MedicController, SpecializareController, PacientController,
    ProgramareController, RetetaController, DiagnosticController, TratamentController, TrimitereController, 
    PacientSolicitareController, MedicSolicitareController
};

Route::view('/', 'index')->name('index');
Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// dashboard-uri diferite
Route::middleware(['auth', 'rol:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'rol:medic'])->group(function () {
    Route::get('/medic/dashboard', [MedicController::class, 'dashboard'])
        ->name('medic.dashboard');
});


Route::middleware(['auth','rol:admin'])->group(function() {
    Route::resource('medici', MedicController::class);
    Route::resource('specializari', SpecializareController::class);
});

Route::middleware(['auth','rol:medic'])->group(function() {
    Route::get('pacienti/existent',[PacientController::class, 'createExistent'])->name('pacienti.existent');
    Route::post('pacienti/existent/{pacient}',[PacientController::class, 'storeExistent'])->name('pacienti.existent.store');
    Route::get('pacienti/{pacient}/istoric', [PacientController::class, 'istoric'])->name('pacienti.istoric');
    Route::resource('pacienti', PacientController::class); 
    Route::resource('programari', ProgramareController::class);
    Route::resource('retete', RetetaController::class);
    Route::resource('diagnostice', DiagnosticController::class);
    Route::resource('tratamente', TratamentController::class);
    Route::resource('trimiteri', TrimitereController::class);
});

Route::middleware(['auth','rol:pacient'])->group(function() {
    Route::get('/pacient/dashboard', [PacientController::class, 'dashboard'])->name('pacient.dashboard');
    Route::get('/pacient/istoric', [PacientController::class, 'istoric'])->name('pacient.istoric');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cont', [ContController::class, 'show'])->name('cont');
    Route::get('/cont/edit', [ContController::class, 'edit'])->name('cont.edit');
    Route::post('/cont/update', [ContController::class, 'update'])->name('cont.update');
    Route::delete('/cont/delete', [ContController::class, 'destroy'])->name('cont.delete');
});


Route::middleware(['auth'])->group(function () {

    // PACIENT
    Route::get('/solicitare/create',
        [PacientSolicitareController::class, 'create']
    )->name('solicitare.create');

    Route::post('/solicitare/store',
        [PacientSolicitareController::class, 'store']
    )->name('solicitare.store');

    Route::delete('/notificari/{id}', [PacientController::class, 'stergeNotificare'])
        ->name('pacient.notificare.sterge');

    // MEDIC
    Route::get('/medic/solicitari',
        [MedicSolicitareController::class, 'index']
    )->name('medic.solicitari');

    Route::post('/medic/solicitari/{solicitare}/respinge',
        [MedicSolicitareController::class, 'respinge']
    )->name('medic.solicitari.respinge');
});