<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class CustomSearchApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $const;

    public function setUp(): void
    {
        parent::setUp();

        // 定数読み込み
        $this->const = config('const');

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function カスタム検索する()
    {
        $numberConst = $this->const['SEARCH_OPERATOR_LABEL']['NUMBER'];
        $datetimeConst = $this->const['SEARCH_OPERATOR_LABEL']['DATETIME'];

        $param = [
            'profile' => [
                'name'                          => 'ほげほげ',
                'age'                           => 24,
                'age_operator'                  => $numberConst[random_int(0, count($numberConst) - 1)],
                'experience_period'             => 3.5,
                'experience_period_operator'    => $numberConst[random_int(0, count($numberConst) - 1)],
                'sex'                           => 1,
            ],
            'skill' => [
                'name'                          => 'Java',
                'type'                          => 'プログラミング言語',
                'level'                         => 1.5,
                'level_operator'                => $numberConst[random_int(0, count($numberConst) - 1)],
                'experience_period'             => 2.3,
                'experience_period_operator'    => $numberConst[random_int(0, count($numberConst) - 1)],
                'tool'                          => 'Eclipse',
                'platform'                      => 'Ubuntu',
                'system'                        => 'Web',
            ],
            'project' => [
                'title'                         => 'ほげほげ',
                'started_at'                    => '2010-02',
                'started_at_operator'           => $datetimeConst[random_int(0, count($datetimeConst) - 1)],
                'ended_at'                      => '2012-11',
                'ended_at_operator'             => $datetimeConst[random_int(0, count($datetimeConst) - 1)],
                'member'                        => 25,
                'member_operator'               => $numberConst[random_int(0, count($numberConst) - 1)],
                'system'                        => 'クライアント・サーバー',
                'region'                        => 'フロントエンド',
                'role'                          => 'PL',
                'dev_method'                    => 'ウォーターフォール',
                'process'                       => '基本設計',
                'lang_and_fw'                   => 'Java',
                'os_and_mw'                     => 'CentOS',
                'tool'                          => 'Eclipse',
            ],
            'qualification' => [
                'name'                          => 'IPA ITパスポート',
            ],
        ];

        $response = $this->actingAs($this->user)->json('POST', route('search.custom'), $param);

        $response->assertStatus(200);
    }


    /**
     * @test
     */
    public function 検索条件を未指定でカスタム検索する()
    {
        $param = [
            'profile' => [
                'name'                          => null,
                'age'                           => null,
                'age_operator'                  => null,
                'experience_period'             => null,
                'experience_period_operator'    => null,
                'sex'                           => null,
            ],
            'skill' => [
                'name'                          => null,
                'type'                          => null,
                'level'                         => null,
                'level_operator'                => null,
                'experience_period'             => null,
                'experience_period_operator'    => null,
                'tool'                          => null,
                'platform'                      => null,
                'system'                        => null,
            ],
            'project' => [
                'title'                         => null,
                'started_at'                    => null,
                'started_at_operator'           => null,
                'ended_at'                      => null,
                'ended_at_operator'             => null,
                'member'                        => null,
                'member_operator'               => null,
                'system'                        => null,
                'region'                        => null,
                'role'                          => null,
                'dev_method'                    => null,
                'process'                       => null,
                'lang_and_fw'                   => null,
                'os_and_mw'                     => null,
                'tool'                          => null,
            ],
            'qualification' => [
                'name'                          => null,
            ],
        ];

        $response = $this->actingAs($this->user)->json('POST', route('search.custom'), $param);

        $response->assertStatus(200);
    }
}
