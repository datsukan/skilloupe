<?php

namespace Tests\Feature;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class IconApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $profile;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
        // テストプロフィール作成
        $this->profile = factory(Profile::class)->create();
    }

    /**
     * @test
     */
    public function 保存したファイルをダウンロードする()
    {
        Storage::fake('local');

        $icon = UploadedFile::fake()->image('dummy.jpg');
        $path = $icon->store('icons');

        Profile::where('id', $this->profile->id)->update([
            'icon_path' => $path,
            'icon_name' => $icon->getClientOriginalName(),
        ]);

        $response = $this->actingAs($this->user)->json('GET', route('icons.download', ['icon' => $this->user->id]));

        $response->assertStatus(200);
    }
}
