<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('amount');
            $table->boolean('is_percent')->default(false);
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });
     
        Schema::create('order_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('price');
            $table->string('discount_code')->nullable();
            $table->integer('discount_amount')->default(0);
            $table->string('payment_method')->nullable(); 
            $table->string('payment_proof')->nullable();
                    $table->tinyInteger('status')->default(0); 
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('order_subscriptions');
    }
}
