<?php

use App\Models\OrderStatus;
use App\Models\ShipStatus;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->string('name');
            $table->string('phone')->index();
            $table->string('address');
            $table->foreignIdFor(User::class, 'admin_id')->nullable()->constrained('users');
            $table->foreignIdFor(User::class, 'shipper_id')->nullable()->constrained('users');
            $table->foreignIdFor(OrderStatus::class)->constrained()->default(1);
            $table->foreignIdFor(ShipStatus::class)->constrained()->default(1);
            $table->decimal('total_price',15,2)->default(0)->index();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
