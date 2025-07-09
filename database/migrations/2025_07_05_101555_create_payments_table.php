<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percent', 'nominal']);
            $table->decimal('discount_value', 12, 2);
            $table->boolean('is_active');
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->string('external_id')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status');
            $table->string('payment_method');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();
            $table->timestamp('paid_at')->nullable();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('status');
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');
        });

        Schema::create('xendit_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('event_type');
            $table->text('raw_payload');
            $table->timestamp('received_at');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('actor_type', ['admin', 'reseller']);
            $table->unsignedBigInteger('actor_id');
            $table->string('action');
            $table->text('description')->nullable();
           $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('xendit_webhook_logs');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('vouchers');
    }
}
