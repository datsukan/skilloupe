<?php

namespace App\Http\Controllers\Skill;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Gate;
use App\Http\Requests\ShowQualificationListRequest;
use App\Http\Requests\UpdateQualificationRequest;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Qualification;

class QualificationController extends Controller
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
     * @param  \App\Http\Requests\ShowQualificationListRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(ShowQualificationListRequest $request, $id)
    {
        return $this->getQualifications((int) $id);
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
     * @param  \App\Http\Requests\UpdateQualificationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQualificationRequest $request, $id)
    {
        // 認可
        Gate::authorize('update', (int) $id);

        // リクエスト内容を取得
        $input = $request->json()->all();

        // トランザクション
        DB::transaction(function () use ($id, $input) {
            // 既存データ削除
            Qualification::where('user_id', $id)->delete();

            // 登録
            $insertList = [];
            $qualificationList = collect($input['qualification_list']);
            foreach ($qualificationList as $qualification) {
                // 登録データにuser_idをセット
                $insertParam['user_id'] = $id;
                $insertParam['name'] = $qualification;
                $insertParam['created_at'] = Carbon::now();
                $insertParam['updated_at'] = Carbon::now();

                $insertList[] = $insertParam;
            }
            Qualification::insert($insertList);
        });

        // 最新資格情報取得
        $result = $this->getQualifications((int) $id);

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

    /**
     * 指定されたユーザーIDの資格情報を取得する
     *
     * @param integer $id
     * @return array
     */
    private function getQualifications($id)
    {
        $result = User::find((int) $id)->qualification;
        return collect($result)->pluck('name')->unique();
    }
}
