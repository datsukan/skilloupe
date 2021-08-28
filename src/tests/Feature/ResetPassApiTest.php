<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPassApiTest extends TestCase
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
    public function パスワードを更新する()
    {
        $data = [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.resetPass', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function パスワード再入力が誤っている場合にバリデーションエラーを返却する()
    {
        $data = [
            'password' => 'test1234',
            'password_confirmation' => 'test1235',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.resetPass', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * @test
     */
    public function パスワードの値が不足している場合にバリデーションエラーを返却する()
    {
        $data = [
            'password' => '',
            'password_confirmation' => 'hoge@1234',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.resetPass', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * @test
     */
    public function パスワード再入力の値が不足している場合にバリデーションエラーを返却する()
    {
        $data = [
            'password' => 'hoge@1234',
            'password_confirmation' => '',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.resetPass', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * @test
     */
    public function 必須項目のキーが不足している場合にバリデーションエラーを返却する()
    {
        $data = [];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.resetPass', ['id' => $this->user->id]), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
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

        // 管理権限 OFFのユーザーでパスワード更新
        $user = User::where('email', $userData['email'])->first();

        $passData = [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->actingAs($user)
            ->json('PUT', route('users.resetPass', ['id' => $user->id]), $passData);

        $response
            ->assertStatus(403);
    }
}
