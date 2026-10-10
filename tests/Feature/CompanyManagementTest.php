<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_and_add_companies(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($superAdmin)
            ->get(route('companies.index'))
            ->assertOk()
            ->assertSee('Nama perusahaan');

        $response = $this->actingAs($superAdmin)->post(route('companies.store'), [
            'name' => 'PT Sehat Sentosa',
        ]);

        $response->assertRedirect(route('companies.index'));
        $this->assertDatabaseHas('companies', ['name' => 'PT Sehat Sentosa']);
    }

    public function test_admin_cannot_view_or_add_companies(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('companies.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('companies.store'), ['name' => 'PT Tidak Diizinkan'])
            ->assertForbidden();

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_company_name_is_required_and_unique(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        Company::create(['name' => 'PT Sehat Sentosa']);

        $this->actingAs($superAdmin)
            ->from(route('companies.index'))
            ->post(route('companies.store'), ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->actingAs($superAdmin)
            ->from(route('companies.index'))
            ->post(route('companies.store'), ['name' => 'PT Sehat Sentosa'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('companies', 1);
    }
}
