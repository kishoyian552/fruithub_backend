<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();  // ✅ Add this
            $table->string('last_name')->nullable();   // ✅ Add this
            $table->string('name')->nullable();        // optional fallback
            $table->string('email')->unique();
            $table->string('phone')->nullable();       // ✅ Add this
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
