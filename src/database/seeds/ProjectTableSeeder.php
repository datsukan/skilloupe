<?php

use Illuminate\Database\Seeder;
use App\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ja_JP');

        $const = config('const');
        $constSystem = $const['PROJECT']['SYSTEM'];
        $constRegion = $const['PROJECT']['REGION'];
        $constRole = $const['PROJECT']['ROLE'];
        $constDevMethod = $const['PROJECT']['DEV_METHOD'];
        $constProcess = $const['PROJECT']['PROCESS'];
        $constLangAndFw = $const['PROJECT']['LANG_AND_FW'];
        $constOsAndMw = $const['PROJECT']['OS_AND_MW'];
        $constTool = $const['PROJECT']['TOOL'];

        for ($i = 1; $i <= 20; $i++) {
            $numberOfSkills = random_int(1, 20);

            for ($j = 1; $j <= $numberOfSkills; $j++) {
                $endedAt = $faker->dateTimeThisDecade();
                $startedAt = $faker->dateTimeThisDecade($endedAt);

                $insertParam = [
                    'user_id'           => $i,
                    'title'             => $this->generateTitle(),
                    'started_at'        => $startedAt,
                    'ended_at'          => $endedAt,
                    'summary'           => $this->generateSummary(),
                    'member'            => random_int(1, 40),
                    'system'            => $this->generateItemArray($constSystem),
                    'region'            => $this->generateItemArray($constRegion),
                    'role'              => $this->generateItemArray($constRole),
                    'dev_method'        => $this->generateItemArray($constDevMethod),
                    'process'           => $this->generateItemArray($constProcess),
                    'lang_and_fw'       => $this->generateItemArray($constLangAndFw),
                    'os_and_mw'         => $this->generateItemArray($constOsAndMw),
                    'tool'              => $this->generateItemArray($constTool),
                    'experience_detail' => $this->generateExperienceDetail(),
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];

                Project::create($insertParam);
            }
        }
    }

    private function generateItemArray($constItems)
    {
        $result = [];
        $numberOfItems = random_int(1, 10);

        for ($i = 0; $i < $numberOfItems; $i++) {
            $result[] = $constItems[random_int(0, (count($constItems) - 1))];
        }

        // 重複削除
        $result = array_values(array_unique($result));
        // 重複削除の結果0件になった場合は1件追加する
        if (count($result) === 0) {
            $result[] = $constItems[0];
        }

        return $result;
    }

    private function generateTitle()
    {
        $samples = [
            '生命保険会社向け　ポータルサイト、お問合せ管理システムの改修・構築',
            'イベント用チケット販売・管理システムの開発',
            '自動車メーカーのコネクテッドカー向け認証基盤システムの開発',
            '業務基盤・顧客管理システムの開発・運用・保守',
            '臨床検査システムの運用・保守・監視',
            'ホテル宿泊予約マーケティングシステム開発',
            'クラウド型プロジェクト管理ツール(動画・画像)',
            '営業放送システム開発',
            'Web証券システム開発',
            '防災地図情報システム開発',
            'ビジネスマッチングシステムの開発',
            '家計簿WEBサービス開発',
            '鉄道会社様向け遅延情報システムの構築',
            '旅行会社社内システム構築',
            '電気機器メーカー社内システム構築',
            'マーケティング会社向けマッシュアップサイトの構築',
            '新築マンション検索サイトリニューアル',
            '設備保全管理システム導入',
            '新卒採用Webサイトシステム開発',
            '通信社 記事作成システム開発',
            '健康保険組合 Webサイト作成',
            '健康保険組合 業務管理Webシステム開発',
            '警備サービス業 入退館管理システム開発',
            '人事評価系システム開発',
            '通信会社ファームウェア更新 テスト支援',
            '認証基盤構築PoC案件',
            'マンガタイトルのVR/ARスマホアプリ',
            '医療系コミュニケーションシステム',
            '株取引スマートフォンアプリ',
            'カーナビ向けアプリ配信システム',
            'ATM監視システム',
        ];

        return $samples[random_int(0, count($samples) - 1)];
    }

    private function generateSummary()
    {
        $samples = [
            "生命保険会社が利用会社向けに提供しているポータルサイトの改修、"
                . "およびポータルサイト利用に関するお問合せ管理用のシステムを開発。",
            "イベントで利用者が使用できるチケットの販売、および利用を行うためのWebサービスおよび管理システムを開発。",
            "コネクテッドカーサービスを利用する各自動車の車載器、"
                . "スマートフォンアプリ、Web サイトからアクセスされる共通の認証基盤、"
                . "および自動車オーナーが購入したサブスクリプションサービスを管理するシステムを開発。",
            "勤怠情報を取り込み、給与計算および前払情報管理を行い、全銀データファイルを出力して入金に必要な情報作成を行うシステムの作成",
            "既存サイトに期間限定のキャンペーンページを追加。\n"
                . "短期間の追加開発を複数件担当。\n"
                . "ページ内にゲームを設置し、ゲーム利用制限やゲームの結果を保存する機能などを実装。",
            "不動産情報メディア内の新築マンション領域における、新築マンション検索サイトおよび入稿システムのリニューアル開発。\n"
                . "既存開発会社からの運用引継ぎ。",
            "ブランド・貴金属リユース企業の買い取り商品マスタ管理システム構築。\n"
                . "SalesForceで構築して運用している既存システムを、同等機能でスクラッチ開発するプロジェクトの第一弾。",
            "日用品メーカーが運営する子育てママ向けSNSサイトのリニューアルおよび運用保守。\n"
                . "常駐先プロパーを中心に、運用チーム開発チーム一体の体制実現。既存開発会社からの引継ぎおよび内製化実現。",
            "国内の石油精製、石油化学など工場・プラントへの設備保全管理システム（CMMS）導入および機能アドオンカスタマイズ。\n"
                . "施設内の機器（＝設備）を一元管理し、メンテナンス計画と実施履歴を蓄積してメンテナンスコストを最適化するための自社パッケージ製品。",
            "防災地図情報システムは、災害時に区役所等で仕様されるシステム。\n"
                . "DB等の資源はサーバで管理し、クライアントはWWWブラウザを利用してアプリケーションをダウンロードし起動する構成となっているWebシステム。\n"
                . "クライアント側の言語はC++言語。\n"
                . "クライアントアプリケーションにはプラグインで機能を追加し、カスタマイズ可能。",
            "海外LNGプラントへの設備資産管理システム（EAM）構築および導入支援。\n"
                . "他社パッケージ製品。",
        ];

        return $samples[random_int(0, count($samples) - 1)];
    }

    private function generateExperienceDetail()
    {
        $samples = [
            "システムのデータ移行の設計、開発、テストをSEとして担当しました。\n主な役割は、詳細設計、開発、テストを担当しました。",
            "システムを利用しているグループ会社からの問い合わせ、調査依頼の対応。\n定期運用作業の実施。",
            "統合認証システムのサーバ移行のため、旧システムから新システムへの移行プログラム等の設計、開発、テストを担当。",
            "PMとしてプロジェクトの課題整理。クライアントとの連携。\nタスク管理・進捗管理。\n要員管理。",
            "開発環境の構築、フレームワークを用いたAPI・画面・バッチの実装を経験。",
        ];

        return $samples[random_int(0, count($samples) - 1)];
    }
}
