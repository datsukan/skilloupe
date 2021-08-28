<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class UpdateQualificationApiTest extends TestCase
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
    public function スキル：資格を空登録する()
    {
        $data = [];

        $data['qualification_list'] = [];

        $response = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキル：資格を登録する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['qualification_list'][] = Str::random(random_int(5, 50));
        }

        $response = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキル：資格を更新する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['qualification_list'][] = Str::random(random_int(5, 50));
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['qualification_list'][] = Str::random(random_int(5, 50));
        }

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }


    /**
     * @test
     */
    public function スキル：資格を削除する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['qualification_list'][] = Str::random(random_int(5, 50));
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $data = [];

        $data['qualification_list'] = [];

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分以外のスキル：資格を登録する場合は認可エラーを返却する()
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

        // 管理権限 OFFのユーザーでスキル：資格更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['qualification_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 管理権限があるユーザーが自分以外のスキル：資格を登録する場合は認可エラーを返却しない()
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

        // 管理権限 OFFのユーザーでスキル：資格更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['qualification_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('qualifications.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分のスキル：資格を登録する場合は認可エラーを返却しない()
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

        // 管理権限 OFFのユーザーでスキル：資格更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['qualification_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('qualifications.update', ['skill' => $user->id]), $data);

        $response->assertStatus(201);
    }
}
