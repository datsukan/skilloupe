<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
