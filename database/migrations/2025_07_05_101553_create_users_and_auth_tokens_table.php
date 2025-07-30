<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAndAuthTokensTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->boolean('is_super_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('google_id')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('pfp_path');
            $table->unsignedTinyInteger('plan_type')->default(0); 
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('resellers');
        Schema::dropIfExists('admins');
    }
}
