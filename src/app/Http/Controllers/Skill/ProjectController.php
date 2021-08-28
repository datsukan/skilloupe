<?php

namespace App\Http\Controllers\Skill;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use App\Http\Requests\ShowProjectListRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Project;

class ProjectController extends Controller
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
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\ShowProjectListRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(ShowProjectListRequest $request, $id)
    {
        $result = User::find((int) $id)->project;

        foreach ($result as $project) {
            $project->started_at = date('Y-m', strtotime($project->started_at));
            $project->ended_at = date('Y-m', strtotime($project->ended_at));
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        // 認可
        Gate::authorize('update', (int) $id);

        // リクエスト内容を取得
        $input = $request->json()->all();

        // トランザクション
        DB::transaction(function () use ($id, $input) {
            // 既存データ削除
            Project::where('user_id', $id)->delete();

            // 登録
            $insertList = $input['project_list'];
            foreach ($insertList as $project) {
                // 登録データにuser_idをセット
                $project['user_id'] = $id;
                // 開始年月・終了年月の型をY-m-dに変換
                $project['started_at'] = date('Y-m-d', strtotime($project['started_at']));
                $project['ended_at'] = date('Y-m-d', strtotime($project['ended_at']));
                Project::create($project);
            }
            // insertはMySQLのJSON型へ上手く変換してくれない
            // Project::insert($insertList);
        });

        // 最新プロジェクト取得
        $result = User::find((int) $id)->project;

        foreach ($result as $project) {
            $project->started_at = date('Y-m', strtotime($project->started_at));
            $project->ended_at = date('Y-m', strtotime($project->ended_at));
        }

        return response()->json($result, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
