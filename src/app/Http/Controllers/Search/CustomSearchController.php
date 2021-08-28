<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomSearchRequest;
use App\User;
use App\Profile;
use App\Skill;
use App\Project;
use App\Qualification;

class CustomSearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * カスタム検索条件に該当する情報を保有しているユーザーのIDリストを取得する
     *
     * @param \App\Http\Requests\CustomSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function search(CustomSearchRequest $request)
    {
        // リクエスト内容を取得
        $input = $request->json()->all();

        // 検索
        $result = $this->customSearch($input);

        return $result;
    }

    /**
     * 全ユーザー情報から完全一致するユーザーを検索する
     *
     * @param array $input
     * @return array $userIds
     */
    private function customSearch($input)
    {
        // ユーザーテーブルの検索
        $user = $this->searchUser($input['profile']);

        // プロフィールテーブルの検索
        $profile = $this->searchProfile($input['profile']);

        // スキルテーブルの検索
        $skill = $this->searchSkill($input['skill']);

        // プロジェクトテーブルの検索
        $project = $this->searchProject($input['project']);

        // 資格テーブルの検索
        $qualification = $this->searchQualification($input['qualification']);

        // 検索条件が指定されているかを取得する
        $hasUserParam = count($this->makeUserWhereParam($input['profile'])) > 0;
        $hasProfileParam = count($this->makeProfileWhereParam($input['profile'])) > 0;
        $hasSkillParam = count($this->makeSkillWhereParam($input['skill'])) > 0;
        $hasProjectParam = count($this->makeProjectWhereParam($input['project'])) > 0;
        $hasQualificationParam = count($this->makeQualificationWhereParam($input['qualification'])) > 0;

        $map = [
            ['ids' => $user, 'hasParam' => $hasUserParam],
            ['ids' => $profile, 'hasParam' => $hasProfileParam],
            ['ids' => $skill, 'hasParam' => $hasSkillParam],
            ['ids' => $project, 'hasParam' => $hasProjectParam],
            ['ids' => $qualification, 'hasParam' => $hasQualificationParam],
        ];

        $result = [];

        // 全ての条件に該当するIDを取得する
        foreach ($map as $tableInfo) {
            if ($tableInfo['hasParam']) {
                if (count($result) === 0) {
                    $result = $tableInfo['ids'];
                } else {
                    $result = collect($result)->merge($tableInfo['ids'])->duplicates()->values();
                }
            }
        }

        return $result;
    }

    /**
     * ユーザーテーブルから完全一致するユーザーを取得してIDリストを返却する
     *
     * @param array $input
     * @return array $result
     */
    private function searchUser($input)
    {
        // 検索条件
        $whereParam = $this->makeUserWhereParam($input);

        // 条件なしであれば終了
        if (count($whereParam) === 0) return [];

        // 検索
        $searchResults = User::where($whereParam)->select('id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('id')->unique();

        return $result;
    }

    /**
     * プロフィールテーブルから完全一致するユーザーを取得してIDリストを返却する
     *
     * @param array $input
     * @return array $result
     */
    private function searchProfile($input)
    {
        // 検索条件を取得する
        $whereParam = $this->makeProfileWhereParam($input);

        // 条件なしであれば終了
        if (count($whereParam) === 0) return [];

        // 検索
        $searchResults = Profile::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id')->unique();

        return $result;
    }

    /**
     * スキルテーブルから完全一致するユーザーを取得してIDリストを返却する
     *
     * @param array $input
     * @return array $result
     */
    private function searchSkill($input)
    {
        // 検索条件
        $whereParam = $this->makeSkillWhereParam($input);

        // 条件なしであれば終了
        if (count($whereParam) === 0) return [];

        // 検索
        $searchResults = Skill::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id')->unique();

        return $result;
    }

    /**
     * プロジェクトテーブルから完全一致するユーザーを取得してIDリストを返却する
     *
     * @param array $input
     * @return array $result
     */
    private function searchProject($input)
    {
        // 検索条件
        $whereParam = $this->makeProjectWhereParam($input);

        // 条件なしであれば終了
        if (count($whereParam) === 0) return [];

        // 検索
        $searchResults = Project::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id')->unique();

        return $result;
    }

    /**
     * 資格テーブルから完全一致するユーザーを取得してIDリストを返却する
     *
     * @param array $input
     * @return array $result
     */
    private function searchQualification($input)
    {
        // 検索条件
        $whereParam = $this->makeQualificationWhereParam($input);

        // 条件なしであれば終了
        if (count($whereParam) === 0) return [];

        // 検索
        $searchResults = Qualification::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id')->unique();

        return $result;
    }

    /**
     * ユーザーテーブルへの検索条件を作成する
     *
     * @param array $input
     * @return array $whereParam
     */
    private function makeUserWhereParam($input)
    {
        $whereParam = [];
        if (!empty($input['name'])) {
            $whereParam[] = ['name', $input['name']];
        }

        return $whereParam;
    }

    /**
     * プロフィールテーブルへの検索条件を作成する
     *
     * @param array $input
     * @return array $whereParam
     */
    private function makeProfileWhereParam($input)
    {
        $whereParam = [];
        if (!empty($input['age'])) {
            $operator = $this->const['SEARCH_OPERATOR']['NUMBER'][$input['age_operator']];
            $whereParam[] = ['age', $operator, $input['age']];
        }
        if (!empty($input['experience_period'])) {
            $operator = $this->const['SEARCH_OPERATOR']['NUMBER'][$input['experience_period_operator']];
            $whereParam[] = ['experience_period', $operator, $input['experience_period']];
        }
        if (!empty($input['self_introduction'])) {
            $whereParam[] = ['self_introduction', $input['self_introduction']];
        }

        return $whereParam;
    }

    /**
     * スキルテーブルへの検索条件を作成する
     *
     * @param array $input
     * @return array $whereParam
     */
    private function makeSkillWhereParam($input)
    {
        $whereParam = [];
        if (!empty($input['name'])) {
            $whereParam[] = ['name', $input['name']];
        }
        if (!empty($input['type'])) {
            $whereParam[] = ['type', $input['type']];
        }
        if (!empty($input['level'])) {
            $operator = $this->const['SEARCH_OPERATOR']['NUMBER'][$input['level_operator']];
            $whereParam[] = ['level', $operator, $input['level']];
        }
        if (!empty($input['experience_period'])) {
            $operator = $this->const['SEARCH_OPERATOR']['NUMBER'][$input['experience_period_operator']];
            $whereParam[] = ['experience_period', $operator, $input['experience_period']];
        }
        if (!empty($input['tool'])) {
            $whereParam[] = ['tool', 'like', '%' . $input['tool'] . '%'];
        }
        if (!empty($input['platform'])) {
            $whereParam[] = ['platform', 'like', '%' . $input['platform'] . '%'];
        }
        if (!empty($input['system'])) {
            $whereParam[] = ['system', 'like', '%' . $input['system'] . '%'];
        }

        return $whereParam;
    }

    /**
     * プロジェクトテーブルへの検索条件を作成する
     *
     * @param array $input
     * @return array $whereParam
     */
    private function makeProjectWhereParam($input)
    {
        $whereParam = [];
        if (!empty($input['title'])) {
            $whereParam[] = ['title', $input['title']];
        }
        if (!empty($input['started_at'])) {
            $operator = $this->const['SEARCH_OPERATOR']['DATETIME'][$input['started_at_operator']];
            $whereParam[] = ['started_at', $operator, date('Y-m-d', strtotime($input['started_at']))];
        }
        if (!empty($input['ended_at'])) {
            $operator = $this->const['SEARCH_OPERATOR']['DATETIME'][$input['ended_at_operator']];
            $whereParam[] = ['ended_at', $operator, date('Y-m-d', strtotime($input['ended_at']))];
        }
        if (!empty($input['member'])) {
            $operator = $this->const['SEARCH_OPERATOR']['NUMBER'][$input['member_operator']];
            $whereParam[] = ['member', $input['member']];
        }
        if (!empty($input['system'])) {
            $whereParam[] = ['system', 'like', '%' . $input['system'] . '%'];
        }
        if (!empty($input['region'])) {
            $whereParam[] = ['region', 'like', '%' . $input['region'] . '%'];
        }
        if (!empty($input['role'])) {
            $whereParam[] = ['role', 'like', '%' . $input['role'] . '%'];
        }
        if (!empty($input['dev_method'])) {
            $whereParam[] = ['dev_method', 'like', '%' . $input['dev_method'] . '%'];
        }
        if (!empty($input['process'])) {
            $whereParam[] = ['process', 'like', '%' . $input['process'] . '%'];
        }
        if (!empty($input['lang_and_fw'])) {
            $whereParam[] = ['lang_and_fw', 'like', '%' . $input['lang_and_fw'] . '%'];
        }
        if (!empty($input['os_and_mw'])) {
            $whereParam[] = ['os_and_mw', 'like', '%' . $input['os_and_mw'] . '%'];
        }
        if (!empty($input['tool'])) {
            $whereParam[] = ['tool', 'like', '%' . $input['tool'] . '%'];
        }

        return $whereParam;
    }

    /**
     * 資格テーブルへの検索条件を作成する
     *
     * @param array $input
     * @return array $whereParam
     */
    private function makeQualificationWhereParam($input)
    {
        $whereParam = [];
        if (!empty($input['name'])) {
            $whereParam[] = ['name', $input['name']];
        }

        return $whereParam;
    }
}
