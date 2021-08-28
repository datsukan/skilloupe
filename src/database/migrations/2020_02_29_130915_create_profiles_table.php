<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('プロフィールID');
            $table->bigInteger('user_id')->comment('ユーザーID');
            $table->unsignedTinyInteger('sex')->nullable()->comment('性別');
            $table->unsignedInteger('age')->comment('年齢');
            $table->unsignedInteger('experience_period')->comment('IT業界歴');
            $table->string('self_introduction', 500)->nullable()->comment('自己紹介');
            $table->string('icon_path')->nullable()->comment('アイコンファイルパス');
            $table->string('icon_name')->nullable()->comment('アイコンファイル名');
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
        Schema::dropIfExists('profiles');
    }
}
