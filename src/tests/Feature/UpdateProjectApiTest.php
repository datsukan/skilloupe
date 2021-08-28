<?php

namespace Tests\Feature;

use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class UpdateProjectApiTest extends TestCase
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
    public function スキル：プロジェクトを空登録する()
    {
        $data = [];

        $data['project_list'] = [];

        $response = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキル：プロジェクトを登録する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['project_list'][] = [
                'title'             => Str::random(random_int(5, 50)),
                'started_at'        => '2010-01',
                'ended_at'          => '2020-12',
                'summary'           => Str::random(random_int(10, 500)),
                'member'            => random_int(1, 500),
                'system'            => [Str::random(random_int(3, 10))],
                'region'            => [Str::random(random_int(3, 10))],
                'role'              => [Str::random(random_int(3, 10))],
                'dev_method'        => [Str::random(random_int(3, 10))],
                'process'           => [Str::random(random_int(3, 10))],
                'lang_and_fw'       => [Str::random(random_int(3, 10))],
                'os_and_mw'         => [Str::random(random_int(3, 10))],
                'tool'              => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $response = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキル：プロジェクトを更新する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['project_list'][] = [
                'title'             => Str::random(random_int(5, 50)),
                'started_at'        => '2010-01',
                'ended_at'          => '2020-12',
                'summary'           => Str::random(random_int(10, 500)),
                'member'            => random_int(1, 500),
                'system'            => [Str::random(random_int(3, 10))],
                'region'            => [Str::random(random_int(3, 10))],
                'role'              => [Str::random(random_int(3, 10))],
                'dev_method'        => [Str::random(random_int(3, 10))],
                'process'           => [Str::random(random_int(3, 10))],
                'lang_and_fw'       => [Str::random(random_int(3, 10))],
                'os_and_mw'         => [Str::random(random_int(3, 10))],
                'tool'              => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $projects = User::find((int) $this->user->id)->project;

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['project_list'][] = [
                'id'                => $projects[$i]->id,
                'title'             => Str::random(random_int(5, 50)),
                'started_at'        => '2010-01',
                'ended_at'          => '2020-12',
                'summary'           => Str::random(random_int(10, 500)),
                'member'            => random_int(1, 500),
                'system'            => [Str::random(random_int(3, 10))],
                'region'            => [Str::random(random_int(3, 10))],
                'role'              => [Str::random(random_int(3, 10))],
                'dev_method'        => [Str::random(random_int(3, 10))],
                'process'           => [Str::random(random_int(3, 10))],
                'lang_and_fw'       => [Str::random(random_int(3, 10))],
                'os_and_mw'         => [Str::random(random_int(3, 10))],
                'tool'              => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }


    /**
     * @test
     */
    public function スキル：プロジェクトを削除する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['project_list'][] = [
                'title'             => Str::random(random_int(5, 50)),
                'started_at'        => '2010-01',
                'ended_at'          => '2020-12',
                'summary'           => Str::random(random_int(10, 500)),
                'member'            => random_int(1, 500),
                'system'            => [Str::random(random_int(3, 10))],
                'region'            => [Str::random(random_int(3, 10))],
                'role'              => [Str::random(random_int(3, 10))],
                'dev_method'        => [Str::random(random_int(3, 10))],
                'process'           => [Str::random(random_int(3, 10))],
                'lang_and_fw'       => [Str::random(random_int(3, 10))],
                'os_and_mw'         => [Str::random(random_int(3, 10))],
                'tool'              => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $projects = User::find((int) $this->user->id)->project;

        $data = [];

        $data['project_list'] = [];

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分以外のスキル：プロジェクトを登録する場合は認可エラーを返却する()
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

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['project_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 管理権限があるユーザーが自分以外のスキル：プロジェクトを登録する場合は認可エラーを返却しない()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' =>  'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 1,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['project_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('projects.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分のスキル：プロジェクトを登録する場合は認可エラーを返却しない()
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

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['project_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('projects.update', ['skill' => $user->id]), $data);

        $response->assertStatus(201);
    }
}
