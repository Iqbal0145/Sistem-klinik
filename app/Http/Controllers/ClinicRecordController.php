<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\HealthCheckup;
use App\Models\User;
use App\Models\WorkAccident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClinicRecordController extends Controller
{
    public function createAccident(): View
    {
        return view('accidents.create', [
            'companies' => Company::query()->orderBy('name')->get(),
        ]);
    }

    public function storeAccident(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'department' => ['required', 'string', 'max:255'],
            'occurred_on' => ['required', 'date', 'before_or_equal:today'],
            'chronology' => ['required', 'string', 'max:10000'],
            'injured_body_part' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:10000'],
            'therapy' => ['required', 'string', 'max:10000'],
        ]);

        $employee = User::query()->where('name', $validated['name'])->first();

        WorkAccident::create([
            ...$validated,
            'employee_id' => $employee?->id,
        ]);

        return redirect()->route('dashboard')->with('status', 'Laporan kecelakaan kerja berhasil disimpan.');
    }

    public function checkups(): View
    {
        $checkups = HealthCheckup::query()
            ->with('employee')
            ->latest('checked_on')
            ->latest('id')
            ->paginate(15);

        return view('checkups.index', compact('checkups'));
    }

    public function createCheckup(): View
    {
        $employees = User::query()
            ->where('role', 'employee')
            ->withCount('healthCheckups')
            ->orderBy('name')
            ->get(['id', 'name', 'company_name']);

        return view('checkups.create', compact('employees'));
    }

    public function storeCheckup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'employee')],
            'checked_on' => ['required', 'date', 'before_or_equal:today'],
            'clinic' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:10000'],
            'treatment' => ['required', 'string', 'max:10000'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ], [
            'employee_id.required' => 'Pilih karyawan dari daftar.',
            'employee_id.exists' => 'Karyawan yang dipilih tidak valid.',
        ]);

        $checkup = HealthCheckup::create($validated);

        return to_route('checkups.show', $checkup);
    }

    public function showCheckup(HealthCheckup $healthCheckup): View
    {
        return view('checkups.show', [
            'checkup' => $healthCheckup->load('employee'),
        ]);
    }

    public function employees(): View
    {
        $employees = User::query()
            ->where('role', 'employee')
            ->withCount(['healthCheckups', 'workAccidents'])
            ->orderBy('name')
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function showEmployee(User $user): View
    {
        abort_unless($user->role === 'employee', 404);

        $user->load([
            'healthCheckups' => fn ($query) => $query->latest('checked_on')->latest('id'),
            'workAccidents' => fn ($query) => $query->with('company')->latest('occurred_on')->latest('id'),
        ]);

        return view('employees.show', ['employee' => $user]);
    }
}
