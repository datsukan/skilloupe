<?php

namespace App\Http\Controllers\Skill;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadIconRequest;
use App\User;
use App\Profile;

class IconController extends Controller
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
     * アイコン画像を返却する
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(DownloadIconRequest $request, $id)
    {
        $profile = User::find((int) $id)->profile;

        if (Storage::disk()->exists($profile->icon_path)) {
            $response = response()->file(Storage::path($profile->icon_path));
        } else {
            $response = response()->file(Storage::path('system/default.png'));
        }

        return $response;
    }
}
