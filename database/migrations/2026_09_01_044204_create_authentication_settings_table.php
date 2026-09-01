<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authentication_settings', function (Blueprint $table) {
            $table->id();
            // 3 default methods — exactly one must be active
            $table->string('default_method')->default('email')->comment('email|magic_link|otp');
            // 2 optional
            $table->boolean('passkey_enabled')->default(false);
            $table->boolean('google_sso_enabled')->default(true);
            $table->string('google_client_id')->nullable();
            $table->text('google_client_secret')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_settings');
    }
};
