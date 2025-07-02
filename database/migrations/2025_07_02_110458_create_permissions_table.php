<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        $now = Carbon\Carbon::now();

        $permissions = [

            // Roles
            ['slug' => 'manage-roles'],

            // Statuses
            ['slug' => 'manage-status'],
            ['slug' => 'validate-status'],

            // Permissions
            ['slug' => 'manage-permission'],

            // Assignments
            ['slug'=> 'assign-roles'],
            ['slug' => 'assign-permission'],

            // Users
            ['slug' => 'manage-users'],
        ];

        $data = array_map(function ($permission) use ($now) {
            return array_merge($permission, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $permissions);

        DB::table('permissions')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
