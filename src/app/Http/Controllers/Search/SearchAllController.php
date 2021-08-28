<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchAllRequest;
use App\User;
use App\Profile;
use App\Skill;
use App\Project;
use App\Qualification;

class SearchAllController extends Controller
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
     * 検索ワードに該当する情報を保有しているユーザーのIDリストを取得する
     *
     * @param \App\Http\Requests\SearchAllRequest $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchAllRequest $request)
    {
        // リクエスト内容を取得
        $input = $request->all();

        // 検索
        $result = $this->searchAll($input['word']);

        return $result;
    }

    /**
     * 全ユーザー情報から部分一致するユーザーを検索する
     *
     * @param string $word
     * @return array $userIds
     */
    private function searchAll($word)
    {
        // ユーザー
        $user = $this->searchUser($word);

        // プロフィール
        $profile = $this->searchProfile($word);

        // スキル
        $skill = $this->searchSkill($word);

        // プロジェクト
        $project = $this->searchProject($word);

        // 資格
        $qualification = $this->searchQualification($word);

        // マージ
        // $result = collect($user)->merge(collect($profile)->merge(collect($skill)->merge($project)))->all();
        $result = collect($user)
            ->merge($profile)
            ->merge($skill)
            ->merge($project)
            ->merge($qualification)
            ->all();

        return $result;
    }

    /**
     * ユーザーテーブルから部分一致するユーザーを取得してIDリストを返却する
     *
     * @param string $word
     * @return array $result
     */
    private function searchUser($word)
    {
        // 検索条件
        $whereParam = [
            ['name', 'like', "%$word%", 'or'],
        ];

        // 検索
        $searchResults = User::where($whereParam)->select('id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('id');

        return $result;
    }

    /**
     * プロフィールテーブルから部分一致するユーザーを取得してIDリストを返却する
     *
     * @param string $word
     * @return array $result
     */
    private function searchProfile($word)
    {
        // 検索条件
        $whereParam = [
            ['age', 'like', "%$word%", 'or'],
            ['experience_period', 'like', "%$word%", 'or'],
            ['self_introduction', 'like', "%$word%", 'or'],
        ];

        // 検索
        $searchResults = Profile::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id');

        return $result;
    }

    /**
     * スキルテーブルから部分一致するユーザーを取得してIDリストを返却する
     *
     * @param string $word
     * @return array $result
     */
    private function searchSkill($word)
    {
        // 検索条件
        $whereParam = [
            ['name', 'like', "%$word%", 'or'],
            ['type', 'like', "%$word%", 'or'],
            ['experience_period', 'like', "%$word%", 'or'],
            ['tool', 'like', "%$word%", 'or'],
            ['platform', 'like', "%$word%", 'or'],
            ['system', 'like', "%$word%", 'or'],
            ['experience_detail', 'like', "%$word%", 'or'],
        ];

        // 検索
        $searchResults = Skill::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id');

        return $result;
    }

    /**
     * プロジェクトテーブルから部分一致するユーザーを取得してIDリストを返却する
     *
     * @param string $word
     * @return array $result
     */
    private function searchProject($word)
    {
        // 検索条件
        $whereParam = [
            ['title', 'like', "%$word%", 'or'],
            ['summary', 'like', "%$word%", 'or'],
            ['member', 'like', "%$word%", 'or'],
            ['system', 'like', "%$word%", 'or'],
            ['region', 'like', "%$word%", 'or'],
            ['role', 'like', "%$word%", 'or'],
            ['dev_method', 'like', "%$word%", 'or'],
            ['process', 'like', "%$word%", 'or'],
            ['lang_and_fw', 'like', "%$word%", 'or'],
            ['os_and_mw', 'like', "%$word%", 'or'],
            ['tool', 'like', "%$word%", 'or'],
            ['experience_detail', 'like', "%$word%", 'or'],
        ];

        // 検索
        $searchResults = Project::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id');

        return $result;
    }

    /**
     * 資格テーブルから部分一致するユーザーを取得してIDリストを返却する
     *
     * @param string $word
     * @return array $result
     */
    private function searchQualification($word)
    {
        // 検索条件
        $whereParam = [
            ['name', 'like', "%$word%", 'or'],
        ];

        // 検索
        $searchResults = Qualification::where($whereParam)->select('user_id')->get();

        // ユーザーIDのリストに整形
        $result = collect($searchResults)->pluck('user_id');

        return $result;
    }
}
