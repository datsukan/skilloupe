<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePassApiTest extends TestCase
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
    public function 自分のパスワードを更新する()
    {
        $data = [
            'current_password'      => 'password',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.changePass'), $data);

        $response
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function 現在のパスワードが誤っている場合にバリデーションエラーを返却する()
    {
        $data = [
            'current_password'      => 'password1',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.changePass'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);
    }

    /**
     * @test
     */
    public function パスワード再入力が誤っている場合にバリデーションエラーを返却する()
    {
        $data = [
            'current_password'      => 'password',
            'password'              => 'test1234',
            'password_confirmation' => 'test1235',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.changePass'), $data);

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
            'current_password'      => '',
            'password' => '',
            'password_confirmation' => 'hoge@1234',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('users.changePass'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['current_password', 'password']);
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
            ->json('PUT', route('users.changePass'), $data);

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
            ->json('PUT', route('users.changePass'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['current_password', 'password']);
    }
}
