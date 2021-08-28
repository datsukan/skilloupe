<?php

use Illuminate\Database\Seeder;

class InitializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期ユーザーとして管理ユーザーを登録（神達のアドレス）
        // ユーザーテーブル
        $insertUsersParam = [
            'name'          => '山田 太郎',
            'email'         => 'abcd@example.com',
            'password'      => bcrypt('password'),
            'is_readonly'   => 0,
            'is_manage'     => 1,
            'created_at'    => date('Y/m/d H:i:s'),
            'updated_at'    => date('Y/m/d H:i:s'),
        ];

        DB::table('users')->insert($insertUsersParam);

        // プロフィールテーブル
        $insertProfilesParam = [
            'user_id'           => 1,
            'sex'               => 1,
            'age'               => 21,
            'experience_period' => 0,
            'self_introduction' => '',
            'created_at'        => date('Y/m/d H:i:s'),
            'updated_at'        => date('Y/m/d H:i:s'),
        ];

        DB::table('profiles')->insert($insertProfilesParam);
    }
}
