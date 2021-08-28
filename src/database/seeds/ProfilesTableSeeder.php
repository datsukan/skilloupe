<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertParam = [];

        for ($i = 1; $i <= 20; $i++) {
            $insertParam[] = [
                'user_id'           => $i,
                'sex'               => random_int(1, 2),
                'age'               => random_int(20, 50),
                'experience_period' => random_int(0, 25),
                'self_introduction' => $this->generateSelfIntroduction(),
                'created_at'        => date('Y/m/d H:i:s'),
                'updated_at'        => date('Y/m/d H:i:s'),
            ];
        }

        DB::table('profiles')->insert($insertParam);
    }

    private function generateSelfIntroduction()
    {
        $result = '';

        $samples = [
            'first'     => [
                'IT技術が大好きなWebエンジニアです。',
                'アートやイラストが好きなWebデザイナーです。',
                '業界歴20年のシニアエンジニアです。',
                'プロダクトを作るのに燃えているプロダクトマネージャーです。',
                'ゲームが趣味のモバイルアプリエンジニアです。',
                '業界入りたてのひよっこプログラマーです。',
            ],
            'second'    => [
                '主にWebアプリケーション開発のフロントエンド領域を担当しています。',
                '業務ではWebサイト・Webアプリケーションのデザインを担当することが多いです。',
                '普段は開発チームのリードエンジニアとして、技術の導入からマネジメントまで幅広く行っています。',
                '会社として取り組むプロダクトの発案・プロジェクトの推進を任されています。',
                'コンシューマー向けのAndroid・iOSアプリの開発を得意としています。',
                'Javaをメインとしたプログラミングと体系的なコンピューターサイエンスを学んできました。',
            ],
            'third'     => [
                'よく扱う言語はJavaScriptで、Vue.jsやReactといったフレームワークを用いています。',
                'デザイン・ワイヤーフレームの作成ではFigma・Adobe XDといったツールを用いています。',
                'プロジェクトでの技術選定、開発環境の構築、開発・テスト手法のレクチャーが得意です。',
                '開発視点ではなく、マーケティング視点での課題解決をメインに取り組んでいます。',
                'SwiftによるiOSネイティブ、ReactNativeやFlutterによるクラスプラットフォーム開発が好きです。',
                'まだ経験が浅いためリードエンジニアのサポートのもとでコーディングしたり、テストを行っています。',
            ],
            'fourth'    => [
                'フロントエンドだけでなくバックエンドにも自分の領域を広げて行こうと思っています。',
                'デザインから派生させてフロントエンド領域もキャッチアップしていくつもりです。',
                '技術・キャリアなどで相談があれば遠慮なく声を掛けてください。',
                '会社の成長につながるように俯瞰的な取り組みを行っていきたいです。',
                '最新技術へのキャッチアップを頑張っています。',
                '先輩方から学んでさらにスキルアップしていきたいです。',
            ],
        ];

        $type = random_int(0, 5);
        foreach ($samples as $key => $sample) {
            if ($key === 'first') {
                $result = $result . $sample[$type];
            } else {
                $result = $result . "\n" . $sample[$type];
            }
        }

        return $result;
    }
}
