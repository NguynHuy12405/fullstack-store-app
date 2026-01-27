<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Size::class)->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2)->default(0)->index();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->unique(['product_id', 'color_id', 'size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
