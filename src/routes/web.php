<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// API
Route::group(['prefix' => 'api', 'middleware' => 'log'], function () {
    Auth::routes();

    // ログインユーザー
    Route::get('/user', function () {
        return Auth::user();
    })->name('user');

    // ログイン
    Route::post('/login', 'Auth\LoginController@login')->name('login');

    // ログアウト
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    // パスワード再設定トークン発行
    Route::post('/password/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgotPass');

    // トークンによるパスワード再設定
    Route::post('/password/forgot/reset', 'Auth\ResetPasswordController@reset')->name('forgotPassReset');

    // パスワード再設定（ユーザー本人の操作）
    Route::put('/users/passwords', 'Auth\EditController@changePass')->name('users.changePass');

    // 管理権限を保持している場合
    Route::group(['middleware' => ['auth', 'can:manage']], function () {
        // ユーザー登録
        Route::post('/users', 'Auth\RegisterController@register')->name('users.register');

        // ユーザー更新
        Route::put('/users/{id}', 'Auth\EditController@edit')->name('users.edit');

        // ユーザー削除
        Route::delete('/users/{id}', 'Auth\EditController@destroy')->name('users.destroy');

        // ユーザー参照
        Route::get('/users', 'Auth\ReferenceController@reference')->name('users.reference');

        // パスワード再設定（管理ユーザーの操作）
        Route::put('/users/{id}/passwords', 'Auth\EditController@resetPass')->name('users.resetPass');
    });

    // プロフィール（一覧取得、更新、詳細取得）
    Route::apiResource('/skills', 'Skill\ProfileController');

    // スキル（全件取得、更新）
    // Route::apiResource('/skills/{skill}/each-skills', 'Skill\SkillController');
    Route::get('/skills/{skill}/each-skills', 'Skill\SkillController@index')->name('each-skills.index');
    Route::put('/skills/{skill}/each-skills', 'Skill\SkillController@update')->name('each-skills.update');

    // プロジェクト（全件取得、更新）
    // Route::apiResource('/skills/{skill}/projects', 'Skill\ProjectController');
    Route::get('/skills/{skill}/projects', 'Skill\ProjectController@index')->name('projects.index');
    Route::put('/skills/{skill}/projects', 'Skill\ProjectController@update')->name('projects.update');

    // 資格（全件取得、更新）
    // Route::apiResource('/skills/{skill}/qualifications', 'Skill\QualificationController');
    Route::get('/skills/{skill}/qualifications', 'Skill\QualificationController@index')->name('qualifications.index');
    Route::put('/skills/{skill}/qualifications', 'Skill\QualificationController@update')->name('qualifications.update');

    // 全文検索（ユーザー情報）
    Route::get('/search', 'Search\SearchAllController@search')->name('search');

    // カスタム検索
    Route::post('/search/custom', 'Search\CustomSearchController@search')->name('search.custom');

    // アイコン取得
    Route::get('/icons/{id}', 'Skill\IconController@download')->name('icons.download');

    // 定数取得
    Route::get('/const', 'ConstController@index')->name('const.index');
});

// Vue Router
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
