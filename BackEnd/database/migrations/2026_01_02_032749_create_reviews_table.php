<?php

use App\Models\Product;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('rating')->default(0)->index();
            $table->text('comment')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Product::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'replier')->nullable()->constrained('users');
            $table->text('reply')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('reply_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
