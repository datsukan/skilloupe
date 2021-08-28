<?php

namespace App\Http\Controllers\Skill;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use App\Http\Requests\ShowSkillListRequest;
use App\Http\Requests\UpdateSkillRequest;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Skill;

class SkillController extends Controller
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
     * @param  \App\Http\Requests\ShowSkillListRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(ShowSkillListRequest $request, $id)
    {
        $result = User::find((int) $id)->skill;

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
     * @param  \App\Http\Requests\UpdateSkillRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSkillRequest $request, $id)
    {
        // 認可
        Gate::authorize('update', (int) $id);

        // リクエスト内容を取得
        $input = $request->json()->all();

        // トランザクション
        DB::transaction(function () use ($id, $input) {
            // 既存データ削除
            Skill::where('user_id', $id)->delete();

            // 登録
            $insertList = $input['skill_list'];
            foreach ($insertList as $skill) {
                // 登録データにuser_idをセット
                $skill['user_id'] = $id;

                Skill::create($skill);
            }
            // insertはMySQLのJSON型へ上手く変換してくれない
            // Skill::insert($insertList);
        });

        // 最新スキル取得
        $result = User::find((int) $id)->skill;

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
