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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('menu'); // 'menu', 'admin', 'aprobador'
            $table->unsignedBigInteger('parent_id')->nullable(); // Para submenús
            $table->string('text');
            $table->string('route')->nullable();
            $table->string('match')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->string('can')->nullable();
            $table->timestamps();
        });

        // Seeder embebido
        \MenuEditor\Models\Menu::create([
            'type' => 'menu',
            'text' => 'Inicio',
            'route' => '',
            'order' => 1,
            'icon' => 'home',
            'match' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
