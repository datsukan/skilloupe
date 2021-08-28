<?php

use Illuminate\Database\Seeder;
use App\Skill;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $const = config('const');
        $constSkill = $const['SKILL']['SKILL'];
        $constType = $const['SKILL']['TYPE'];
        $constTool = $const['SKILL']['SKILL'];
        $constPlatform = $const['SKILL']['PLATFORM'];
        $constSystem = $const['SKILL']['SYSTEM'];

        for ($i = 1; $i <= 20; $i++) {
            $numberOfSkills = random_int(1, 20);

            for ($j = 1; $j <= $numberOfSkills; $j++) {
                $insertParam = [
                    'user_id'           => $i,
                    'name'              => $constSkill[random_int(0, (count($constSkill) - 1))],
                    'type'              => $constType[random_int(0, (count($constType) - 1))],
                    'level'             => random_int(0, 5),
                    'experience_period' => random_int(0, 40),
                    'tool'              => $this->generateItemArray($constTool),
                    'platform'          => $this->generateItemArray($constPlatform),
                    'system'            => $this->generateItemArray($constSystem),
                    'experience_detail' => $this->generateExperienceDetail(),
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];

                Skill::create($insertParam);
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

    private function generateExperienceDetail()
    {
        $samples = [
            "SPAのWebアプリ開発経験あり",
            "導入～PHPアプリ連動の設定まで経験あり",
            "ソース解析、一部修正経験あり",
            "開発リーダー経験あり",
            "PJ経験ほぼなし",
        ];

        return $samples[random_int(0, count($samples) - 1)];
    }
}
