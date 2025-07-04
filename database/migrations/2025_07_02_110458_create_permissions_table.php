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
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        $now = Carbon\Carbon::now();

        $permissions = [

            // Roles
            ['name'=>'Manage Roles','slug' => 'manage-roles'],

            // Statuses
            ['name'=>'Manage Status','slug' => 'manage-status'],
            ['name'=>'Validate Status','slug' => 'validate-status'],

            // Permissions
            ['name'=>'Manage Permissions','slug' => 'manage-permission'],

            // Assignments
            ['name'=>'Assign Roles','slug' => 'assign-roles'],
            ['name'=>'Assign Permissions','slug' => 'assign-permission'],

            // Users
            ['name'=>'Manage Users','slug' => 'manage-users'],
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
