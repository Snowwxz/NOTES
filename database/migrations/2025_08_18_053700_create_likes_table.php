<?php
// database/migrations/xxxx_xx_xx_create_likes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['note_id', 'user_id']); // biar 1 user cuma bisa 1x like
        });
    }

    public function down() {
        Schema::dropIfExists('likes');
    }
};
