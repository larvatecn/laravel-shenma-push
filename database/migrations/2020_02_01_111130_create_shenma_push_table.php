<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShenmaPushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shenma_push', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->unique();
            $table->string('type');
            $table->tinyInteger('status')->default(0)->nullable()->index();
            $table->string('msg')->nullable();
            $table->unsignedInteger('failures')->nullable()->default(0)->comment('失败计数');
            $table->boolean('included')->nullable()->default(false);
            $table->timestamp('push_at', 0)->nullable();//推送时间
            $table->timestamp('created_at', 0)->nullable();//创建时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shenma_push');
    }
}
