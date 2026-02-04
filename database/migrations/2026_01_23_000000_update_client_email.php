<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove old user
        DB::table('clients')->where('email', 'r.arijit2000@gmail.com')->delete();

        // Add new user if not exists
        if (!DB::table('clients')->where('email', 'sriramdev.tech@gmail.com')->exists()) {
            DB::table('clients')->insert([
                'email' => 'sriramdev.tech@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove new user
        DB::table('clients')->where('email', 'sriramdev.tech@gmail.com')->delete();

        // Add old user if not exists
        if (!DB::table('clients')->where('email', 'r.arijit2000@gmail.com')->exists()) {
            DB::table('clients')->insert([
                'email' => 'r.arijit2000@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
