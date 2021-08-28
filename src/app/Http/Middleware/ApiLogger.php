<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    protected $const;
    protected $message;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 定数読み込み
        $this->const = config('const');
        // メッセージ読み込み
        $this->message = config('message');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // アクション名を取得
        $action = $request->route()->getActionName();

        // ログ：処理開始
        Log::info($action . $this->message['API_START']);

        // 対象外に指定されていない場合のみログ出力
        if (!$this->isExcludedAction($action)) {
            // ログ：リクエスト
            Log::info($this->message['API_REQUEST'] . json_encode($request->all(), JSON_UNESCAPED_UNICODE));
        }

        // コントローラー処理
        $response = $next($request);

        // ログ：レスポンス
        Log::info($this->message['API_RESPONSE'] . json_encode($response, JSON_UNESCAPED_UNICODE));

        // ログ：処理終了
        Log::info($action . $this->message['API_END']);

        return $response;
    }

    /**
     * 指定されたアクションがリクエストログの除外対象か判定する
     *
     * @param string $action
     * @return bool
     */
    private function isExcludedAction($action): bool
    {
        // リクエストログの対象外を指定
        // → コントローラー名@アクションメソッド名
        $excludeActions = [
            'Auth\LoginController@login',
            'Auth\RegisterController@register',
            'Auth\EditController@resetPass',
        ];

        $result = false;

        foreach ($excludeActions as $excludeAction) {
            if (strpos($action, $excludeAction) !== false) {
                $result = true;
            }
        }

        return $result;
    }
}
