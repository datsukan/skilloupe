<?php

use Illuminate\Database\Seeder;

class QualificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $const = config('const');
        $constQualification = $const['QUALIFICATION'];

        $insertParam = [];

        for ($i = 1; $i <= 20; $i++) {
            $insertQualification = $this->generateItemArray($constQualification);
            foreach ($insertQualification as $qualification) {
                $insertParam[] = [
                    'user_id'           => $i,
                    'name'              => $qualification,
                    'created_at'        => date('Y/m/d H:i:s'),
                    'updated_at'        => date('Y/m/d H:i:s'),
                ];
            }
        }

        DB::table('qualifications')->insert($insertParam);
    }

    private function generateItemArray($constItems)
    {
        $result = [];
        $numberOfItems = random_int(1, 5);

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
}
