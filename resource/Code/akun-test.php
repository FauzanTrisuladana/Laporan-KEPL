<?php

namespace Tests\Feature;

use App\Models\Akun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AkunTest extends TestCase
{
    use RefreshDatabase;

    private function getBendaharaToken(): string
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'status' => 'Aktif',
        ]);

        return $bendahara->createToken('auth_token')->plainTextToken;
    }

    public function test_bendahara_can_list_akun_filtered_by_kas(): void
    {
        $token = $this->getBendaharaToken();

        Akun::create([
            'nama_akun' => 'Kas Utama Pemuda',
            'kas' => 'kas pemuda',
            'keterangan' => 'Rekening utama',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/akun?page=1&per_page=10&kas[]=kas pemuda');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama_akun', 'Kas Utama Pemuda');
    }

    public function test_bendahara_can_create_akun(): void
    {
        $token = $this->getBendaharaToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/akun', [
                'nama_akun' => 'Bank BRI Pemuda',
                'kas' => 'kas pemuda',
                'keterangan' => 'Tabungan organisasi',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Akun berhasil dibuat')
            ->assertJsonPath('data.nama_akun', 'Bank BRI Pemuda');

        $this->assertDatabaseHas('akun', [
            'nama_akun' => 'Bank BRI Pemuda',
            'kas' => 'kas pemuda',
        ]);
    }

    public function test_bendahara_can_show_akun_detail(): void
    {
        $token = $this->getBendaharaToken();
        $akun = Akun::create(['nama_akun' => 'Bank BRI Pemuda', 'kas' => 'kas pemuda']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/akun/{$akun->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama_akun', 'Bank BRI Pemuda');
    }

    public function test_bendahara_can_update_akun(): void
    {
        $token = $this->getBendaharaToken();
        $akun = Akun::create(['nama_akun' => 'Old Akun', 'kas' => 'kas pemuda']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/akun/{$akun->id}", [
                'nama_akun' => 'Updated Akun',
                'kas' => '17 an',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama_akun', 'Updated Akun');
    }

    public function test_bendahara_can_delete_akun(): void
    {
        $token = $this->getBendaharaToken();
        $akun = Akun::create(['nama_akun' => 'To Delete Akun', 'kas' => 'kas pemuda']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson("/api/akun/{$akun->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertSoftDeleted('akun', ['id' => $akun->id]);
    }
}
