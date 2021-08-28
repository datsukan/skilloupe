<?php

namespace Tests\Feature;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProfileEditApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function プロフィールを登録する()
    {
        Storage::fake('local');

        $data = [
            'icon' => UploadedFile::fake()->image('dummy.jpg'),
            'sex' => random_int(1, 3),
            'age' => random_int(1, 100),
            'experience_period' => random_int(0, 40),
            'self_introduction' => Str::random(random_int(1, 500)),
        ];

        $response = $this->actingAs($this->user)->json('PUT', route('skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);

        $profile = User::find($this->user->id)->profile;

        // DBに挿入されたファイル名のファイルがストレージに保存されていること
        Storage::disk()->assertExists($profile->icon_path);
    }

    /**
     * @test
     */
    public function プロフィールを更新する()
    {
        Storage::fake('local');

        $data = [
            'icon' => UploadedFile::fake()->image('dummy.jpg'),
            'sex' => random_int(1, 3),
            'age' => random_int(1, 100),
            'experience_period' => random_int(0, 40),
            'self_introduction' => Str::random(random_int(1, 500)),
        ];

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('skills.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('skills.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(204);

        $profile = User::find($this->user->id)->profile;

        // DBに挿入されたファイル名のファイルがストレージに保存されていること
        Storage::disk()->assertExists($profile->icon_path);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分以外のプロフィールを登録する場合は認可エラーを返却する()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 0,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでプロフィールを更新
        $user = User::where('email', $userData['email'])->first();

        Storage::fake('local');

        $data = [
            'icon' => UploadedFile::fake()->image('dummy.jpg'),
            'sex' => random_int(1, 3),
            'age' => random_int(1, 100),
            'experience_period' => random_int(0, 40),
            'self_introduction' => Str::random(random_int(1, 500)),
        ];

        $response = $this->actingAs($user)->json('PUT', route('skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 管理権限があるユーザーが自分以外のプロフィールを登録する場合は認可エラーを返却しない()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 1,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでプロフィールを更新
        $user = User::where('email', $userData['email'])->first();

        Storage::fake('local');

        $data = [
            'icon' => UploadedFile::fake()->image('dummy.jpg'),
            'sex' => random_int(1, 3),
            'age' => random_int(1, 100),
            'experience_period' => random_int(0, 40),
            'self_introduction' => Str::random(random_int(1, 500)),
        ];

        $response = $this->actingAs($user)->json('PUT', route('skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);

        $profile = User::find($this->user->id)->profile;

        // DBに挿入されたファイル名のファイルがストレージに保存されていること
        Storage::disk()->assertExists($profile->icon_path);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分のプロフィールを更新する場合は認可エラーを返却しない()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 0,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでプロフィールを更新
        $user = User::where('email', $userData['email'])->first();

        Storage::fake('local');

        $data = [
            'icon' => UploadedFile::fake()->image('dummy.jpg'),
            'sex' => random_int(1, 3),
            'age' => random_int(1, 100),
            'experience_period' => random_int(0, 40),
            'self_introduction' => Str::random(random_int(1, 500)),
        ];

        $response = $this->actingAs($user)->json('PUT', route('skills.update', ['skill' => $user->id]), $data);

        $response->assertStatus(204);

        $profile = User::find($user->id)->profile;

        // DBに挿入されたファイル名のファイルがストレージに保存されていること
        Storage::disk()->assertExists($profile->icon_path);
    }
}
