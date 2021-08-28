<?php

namespace App\Http\Controllers\Skill;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Gate;
use App\Http\Requests\ShowProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\User;
use App\Profile;

class ProfileController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = User::where('is_readonly', false)->get();

        foreach ($result as $key => $user) {
            $result[$key]->profile = User::find((int) $user->id)->profile;
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
     * @param  \App\Http\Requests\ShowProfileRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowProfileRequest $request, $id)
    {
        $result = [];

        // 基本情報の取得
        $result['user'] = User::find((int) $id);

        // プロフィールの取得
        $result['profile'] = User::find((int) $id)->profile;

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateProfileRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        // 認可
        Gate::authorize('update', (int) $id);

        // リクエスト内容を取得
        $input = $request->all();

        // 登録・更新用に入れ直し
        $input['user_id'] = $id;
        unset($input['icon']);

        // アイコンファイルが存在する場合
        if ($request->hasFile('icon')) {
            $icon = $request->icon;

            $input['icon_path'] = $icon->store('icons');
            $input['icon_name'] = $icon->getClientOriginalName();

            // 既に登録済みのアイコンが存在する場合は削除
            $profile = User::find((int) $id)->profile;
            if (isset($profile->icon_path) && Storage::disk()->exists($profile->icon_path)) {
                Storage::delete($profile->icon_path);
            }
        }

        // 既に登録済みの場合
        if (Profile::where('user_id', (int) $id)->exists()) {
            Profile::where('user_id', (int) $id)->update($input);

            return response(null, 204);
        }

        // 未登録の場合
        Profile::create($input);

        // プロフィールの取得
        $result = User::find((int) $id)->profile;


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
