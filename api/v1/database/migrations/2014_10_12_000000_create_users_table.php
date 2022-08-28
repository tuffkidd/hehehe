<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('이름');
            $table->string('nickname')->comment('닉네임');
            $table->string('email')->unique()->comment('이메일');
            $table->timestamp('email_verified_at')->nullable()->comment('이메일 인증날짜');
            $table->string('password');
            $table->enum('type', ['S', 'A', 'U'])->default('U')->comment('S:슈퍼관리자, A: 관리자, U:사용자');
            $table->enum('active_status', ['inactive', 'active', 'lock'])->default('inactive')->comment('inactive:비활성(이메일인증X), active:활성(이메일인증O), lock:잠김-잘못하면 잠금');
            $table->enum('login_platform', ['', 'facebook', 'twitter', 'google', 'naver', 'kakao', 'apple'])->default('')->comment('SNS 로그인 시 사용 플랫폼');
            $table->string('sns_id')->comment('SNS 로그인 시 ID');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
