<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\ProcessIdProcessor;

class CustomizeFormatter
{

    /**
     * ログのフォーマット
     * @var string
     */
    private $logFormat = 'LogLevel:%level_name%	Time:%datetime%	Environment:%channel%	Ip:%extra.ip%	Pid:%extra.process_id%	Program:[%extra.class%::%extra.function%(%extra.line%)]	Message:%message%' . PHP_EOL;

    /**
     * 日付のフォーマット
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s.v';

    /**
     * 渡されたロガーインスタンスのカスタマイズ
     *
     * @param \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($monolog)
    {
        // フォーマットを指定
        $formatter = new LineFormatter($this->logFormat, $this->dateFormat, true, true);

        // extraフィールドの追加
        $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);

        // ip等の情報追加
        $wp = new WebProcessor();

        // プロセスIDの追加
        $pid = new ProcessIdProcessor();

        foreach ($monolog->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            $handler->pushProcessor($ip);
            $handler->pushProcessor($wp);
            $handler->pushProcessor($pid);
        }
    }
}
