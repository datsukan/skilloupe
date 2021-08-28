<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('プロジェクトID');
            $table->bigInteger('user_id')->comment('ユーザーID');
            $table->string('title', 50)->comment('プロジェクト名');
            $table->date('started_at')->comment('開始年月');
            $table->date('ended_at')->comment('終了年月');
            $table->string('summary', 500)->comment('概要');
            $table->unsignedMediumInteger('member')->comment('人数');
            $table->json('system')->nullable()->comment('システム');
            $table->json('region')->nullable()->comment('領域');
            $table->json('role')->nullable()->comment('役割');
            $table->json('dev_method')->nullable()->comment('開発手法');
            $table->json('process')->nullable()->comment('工程');
            $table->json('lang_and_fw')->nullable()->comment('言語・FW');
            $table->json('os_and_mw')->nullable()->comment('OS・MW');
            $table->json('tool')->nullable()->comment('ツール');
            $table->string('experience_detail', 1000)->comment('経験詳細');
            $table->dateTime('created_at')->comment('登録日時');
            $table->dateTime('updated_at')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
