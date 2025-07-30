<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCartsWishlistTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('sub_district');
            $table->string('neighborhood')->nullable();
            $table->string('hamlet')->nullable();
            $table->string('village')->nullable();
            $table->string('zipcode');
            $table->text('address_detail');
            $table->string('sub_district_id');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->integer('total_price');
            $table->tinyInteger('status')->default(0); 
            $table->string('payment_proofs')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('shipping_address');
            $table->text('note')->nullable();
            $table->integer('total_shipping');
            $table->datetime('is_paid_at')->nullable();
            $table->datetime('is_processed_at')->nullable();
            $table->datetime('is_shipped_at')->nullable();
            $table->datetime('is_done_at')->nullable();
            $table->datetime('is_cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('product_name');
            $table->tinyInteger('quantity');
            $table->integer('price_each');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('quantity');
            $table->timestamps();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('resellers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
            Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->text('comment')->nullable();
            $table->text('reply')->nullable();
            $table->datetime('reply_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlist');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('ratings');
    }
}
