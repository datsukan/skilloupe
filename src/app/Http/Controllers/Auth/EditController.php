<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPassRequest;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\DeleteUserRequest;
use App\User;
use App\Profile;
use App\Project;

class EditController extends Controller
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
     * ユーザー情報を更新する
     *
     * @param  App\Http\Requests\UpdateUserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UpdateUserRequest $request, $id)
    {
        // リクエスト内容を取得
        $input = $request->all();

        // 更新
        User::find((int) $id)->update($input);

        return response(null, 204);
    }

    /**
     * パスワードを更新する（管理ユーザーの操作）
     *
     * @param  App\Http\Requests\ResetPassRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPass(ResetPassRequest $request, $id)
    {
        // リクエスト内容を取得
        $input = $request->all();

        $updateParam = [
            "password" => Hash::make($input["password"]),
        ];

        // 更新
        User::find((int) $id)->update($updateParam);

        return response(null, 204);
    }

    /**
     * パスワードを更新する（ユーザー本人の操作）
     *
     * @param  App\Http\Requests\ChangePassRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePass(ChangePassRequest $request)
    {
        // リクエスト内容を取得
        $input = $request->all();

        $updateParam = [
            "password" => Hash::make($input["password"]),
        ];

        // 更新
        User::find(Auth::id())->update($updateParam);

        return response(null, 204);
    }

    /**
     * ユーザーを削除する
     *
     * @param  App\Http\Requests\DeleteUserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request, $id)
    {
        User::destroy((int) $id);
        Profile::where('user_id', (int) $id)->delete();
        Project::where('user_id', (int) $id)->delete();

        return response(null, 200);
    }
}
