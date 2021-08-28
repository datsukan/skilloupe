<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserEditApiTest extends TestCase
{
    use RefreshDatabase;

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
    public function 一般ユーザー情報に更新する()
    {
        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummy@email.com',
            'is_readonly'   => 0,
            'is_manage'     => 0,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function 参照専用一般ユーザー情報に更新する()
    {
        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummy@email.com',
            'is_readonly'   => 1,
            'is_manage'     => 0,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function 管理ユーザー情報に更新する()
    {
        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummy@email.com',
            'is_readonly'   => 0,
            'is_manage'     => 1,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function 参照専用管理ユーザー情報に更新する()
    {
        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummy@email.com',
            'is_readonly'   => 1,
            'is_manage'     => 1,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function emailの形式が不正な場合にバリデーションエラーを返却する()
    {
        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummyemail.com',
            'is_readonly'   => 0,
            'is_manage'     => 0,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function 必須項目の値が不足している場合にバリデーションエラーを返却する()
    {
        $data = [
            'name'          => '',
            'email'         => '',
            'is_readonly'   => null,
            'is_manage'     => null,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'is_readonly', 'is_manage']);
    }

    /**
     * @test
     */
    public function 必須項目のキーが不足している場合にバリデーションエラーを返却する()
    {
        $data = [];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'is_readonly', 'is_manage']);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーの場合は認可エラーを返却する()
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

        // 管理権限 OFFのユーザーでユーザー更新
        $user = User::where('email', $userData['email'])->first();

        $data = [
            'name'          => 'vuesplash user',
            'email'         => 'dummy@email.com',
            'is_readonly'   => 0,
            'is_manage'     => 0,
        ];

        $response = $this->actingAs($user)
            ->json('PUT', route('users.edit', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(403);
    }
}
