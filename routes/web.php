<?php

use App\Http\Controllers\ClinicRecordController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Models\HealthCheckup;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $today = now();
    $todayVisits = HealthCheckup::query()
        ->whereBetween('created_at', [
            $today->copy()->startOfDay()->addMinute(),
            $today->copy()->endOfDay(),
        ])
        ->count();

    return view('dashboard', [
        'todayVisits' => $todayVisits,
        'waitingForCheckup' => $todayVisits,
        'totalEmployees' => User::query()->where('role', 'employee')->count(),
        'latestCheckup' => HealthCheckup::query()->with('employee')->latest('checked_on')->latest('id')->first(),
        'latestEmployee' => User::query()->latest('updated_at')->first(),
    ]);
})->middleware(['auth', 'verified', 'role:admin,super_admin'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin,super_admin'])->group(function () {
    Route::get('/input/kecelakaan-kerja', [ClinicRecordController::class, 'createAccident'])->name('accidents.create');
    Route::post('/input/kecelakaan-kerja', [ClinicRecordController::class, 'storeAccident'])->name('accidents.store');
    Route::get('/input/pemeriksaan-kesehatan', [ClinicRecordController::class, 'createCheckup'])->name('checkups.create');
    Route::post('/input/pemeriksaan-kesehatan', [ClinicRecordController::class, 'storeCheckup'])->name('checkups.store');
    Route::get('/riwayat-pemeriksaan', [ClinicRecordController::class, 'checkups'])->name('checkups.index');
    Route::get('/riwayat-pemeriksaan/{healthCheckup}', [ClinicRecordController::class, 'showCheckup'])->name('checkups.show');
    Route::get('/karyawan', [ClinicRecordController::class, 'employees'])->name('employees.index');
    Route::get('/karyawan/{user}', [ClinicRecordController::class, 'showEmployee'])->name('employees.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:super_admin'])->group(function () {
    Route::get('/perusahaan', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/perusahaan', [CompanyController::class, 'store'])->name('companies.store');
});

require __DIR__.'/auth.php';
