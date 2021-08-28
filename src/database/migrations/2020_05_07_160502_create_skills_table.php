<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('スキルID');
            $table->bigInteger('user_id')->comment('ユーザーID');
            $table->string('name', 50)->comment('スキル名');
            $table->string('type', 50)->comment('スキルタイプ');
            $table->decimal('level', 2, 1)->comment('習熟度');
            $table->decimal('experience_period', 5, 1)->comment('経験期間');
            $table->json('tool')->nullable()->comment('ツール');
            $table->json('platform')->nullable()->comment('プラットフォーム');
            $table->json('system')->nullable()->comment('システム');
            $table->string('experience_detail', 1000)->nullable()->comment('経験詳細');
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
        Schema::dropIfExists('skills');
    }
}
