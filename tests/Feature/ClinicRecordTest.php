<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\HealthCheckup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_work_accident_report_for_an_employee(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $company = Company::create(['name' => 'PT Sehat Sentosa']);
        $employee = User::factory()->create([
            'name' => 'Rina Karyawan',
            'role' => 'employee',
        ]);

        $response = $this->actingAs($admin)->post(route('accidents.store'), [
            'company_id' => $company->id,
            'name' => 'Rina Karyawan',
            'age' => 31,
            'department' => 'Produksi',
            'occurred_on' => now()->subDay()->toDateString(),
            'chronology' => 'Terpeleset di area kerja.',
            'injured_body_part' => 'Pergelangan tangan',
            'action_taken' => 'Dibawa ke klinik perusahaan.',
            'therapy' => 'Kompres dan kontrol lanjutan.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('work_accidents', [
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'name' => 'Rina Karyawan',
            'injured_body_part' => 'Pergelangan tangan',
        ]);
    }

    public function test_employee_directory_only_shows_employee_accounts(): void
    {
        $admin = User::factory()->create(['name' => 'Operator Login', 'role' => 'admin']);
        $otherAdmin = User::factory()->create(['name' => 'Admin Lain', 'role' => 'admin']);
        $superAdmin = User::factory()->create(['name' => 'Super Admin', 'role' => 'super_admin']);
        User::factory()->create([
            'name' => 'Karyawan Tanpa Riwayat',
            'role' => 'employee',
            'company_name' => 'PT Contoh Perusahaan',
        ]);

        $this->actingAs($admin)
            ->get(route('employees.index'))
            ->assertOk()
            ->assertSee('Karyawan Tanpa Riwayat')
            ->assertSee('PT Contoh Perusahaan')
            ->assertDontSee('Admin Lain')
            ->assertDontSee('Super Admin');

        $this->actingAs($admin)
            ->get(route('employees.show', $otherAdmin))
            ->assertNotFound();

        $this->actingAs($admin)
            ->get(route('employees.show', $superAdmin))
            ->assertNotFound();
    }

    public function test_admin_can_create_a_first_checkup_for_an_employee_without_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = User::factory()->create([
            'name' => 'Karyawan Baru Diperiksa',
            'role' => 'employee',
            'company_name' => 'PT Klinik Sejahtera',
        ]);

        $this->actingAs($admin)
            ->get(route('checkups.create'))
            ->assertOk()
            ->assertSee('Karyawan Baru Diperiksa')
            ->assertSee('PT Klinik Sejahtera')
            ->assertSee('Belum ada riwayat');

        $response = $this->actingAs($admin)->post(route('checkups.store'), [
            'employee_id' => $employee->id,
            'checked_on' => now()->toDateString(),
            'clinic' => 'Umum',
            'diagnosis' => 'Pemeriksaan awal.',
            'treatment' => 'Istirahat dan kontrol.',
            'notes' => '',
        ]);

        $checkup = HealthCheckup::query()->where('employee_id', $employee->id)->firstOrFail();
        $response->assertRedirect(route('checkups.show', $checkup));
        $this->assertDatabaseHas('health_checkups', [
            'employee_id' => $employee->id,
            'diagnosis' => 'Pemeriksaan awal.',
        ]);
    }

    public function test_checkup_cannot_be_assigned_to_an_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('checkups.create'))
            ->post(route('checkups.store'), [
                'employee_id' => $admin->id,
                'checked_on' => now()->toDateString(),
                'diagnosis' => 'Diagnosis',
                'treatment' => 'Pengobatan',
            ])
            ->assertSessionHasErrors('employee_id');

        $this->assertDatabaseCount('health_checkups', 0);
    }

    public function test_accident_report_requires_all_requested_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('accidents.create'))
            ->post(route('accidents.store'), [])
            ->assertSessionHasErrors([
                'company_id', 'name', 'age', 'department', 'occurred_on',
                'chronology', 'injured_body_part', 'action_taken', 'therapy',
            ]);

        $this->assertDatabaseCount('work_accidents', 0);
    }

    public function test_accident_report_rejects_a_company_that_does_not_exist(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('accidents.create'))
            ->post(route('accidents.store'), [
                'company_id' => 999,
                'name' => 'Rina Karyawan',
                'age' => 31,
                'department' => 'Produksi',
                'occurred_on' => now()->subDay()->toDateString(),
                'chronology' => 'Terpeleset di area kerja.',
                'injured_body_part' => 'Pergelangan tangan',
                'action_taken' => 'Dibawa ke klinik perusahaan.',
                'therapy' => 'Kompres dan kontrol lanjutan.',
            ])
            ->assertSessionHasErrors('company_id');

        $this->assertDatabaseCount('work_accidents', 0);
    }

    public function test_accident_form_lists_companies_added_by_super_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Company::create(['name' => 'PT Sehat Sentosa']);

        $this->actingAs($admin)
            ->get(route('accidents.create'))
            ->assertOk()
            ->assertSee('PT Sehat Sentosa');
    }

    public function test_non_admin_cannot_open_employee_medical_records(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employee)
            ->get(route('employees.index'))
            ->assertForbidden();
    }

    public function test_employee_detail_shows_company_and_medical_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = User::factory()->create([
            'name' => 'Dewi Perawat',
            'role' => 'employee',
            'company_name' => 'PT Sehat Sentosa',
            'employee_number' => 'KRY-104',
            'department' => 'Operasional',
            'age' => 28,
        ]);
        HealthCheckup::factory()->create([
            'employee_id' => $employee->id,
            'checked_on' => now()->subDay()->toDateString(),
            'diagnosis' => 'Tekanan darah normal.',
            'treatment' => 'Tidak memerlukan tindakan.',
        ]);

        $this->actingAs($admin)
            ->get(route('employees.show', $employee))
            ->assertOk()
            ->assertSee('PT Sehat Sentosa')
            ->assertSee('KRY-104')
            ->assertSee('Tekanan darah normal.')
            ->assertSee('Riwayat pemeriksaan');
    }

    public function test_dashboard_view_links_to_latest_checkup_and_employee_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = User::factory()->create([
            'name' => 'Budi Karyawan',
            'role' => 'employee',
        ]);
        $checkup = HealthCheckup::factory()->create([
            'employee_id' => $employee->id,
            'checked_on' => now()->subDay()->toDateString(),
        ]);
        $latestEmployee = User::query()->latest('updated_at')->first();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('checkups.show', $checkup))
            ->assertSee(route('employees.index'))
            ->assertSee($latestEmployee->name);
    }

    public function test_dashboard_counts_visits_in_today_window_and_employee_accounts(): void
    {
        $this->travelTo(now()->setTime(12, 0));
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(2)->create(['role' => 'employee']);
        $startOfDay = now()->startOfDay();

        HealthCheckup::factory()->create([
            'checked_on' => now()->toDateString(),
            'created_at' => $startOfDay->copy()->setTime(0, 0, 59),
        ]);
        HealthCheckup::factory()->create([
            'checked_on' => now()->toDateString(),
            'created_at' => $startOfDay->copy()->setTime(0, 1),
        ]);
        HealthCheckup::factory()->create([
            'checked_on' => now()->toDateString(),
            'created_at' => $startOfDay->copy()->setTime(23, 59, 59),
        ]);
        HealthCheckup::factory()->create([
            'checked_on' => now()->toDateString(),
            'created_at' => $startOfDay->copy()->addDay(),
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('todayVisits', 2)
            ->assertViewHas('waitingForCheckup', 2)
            ->assertViewHas('totalEmployees', 2);
    }
}
