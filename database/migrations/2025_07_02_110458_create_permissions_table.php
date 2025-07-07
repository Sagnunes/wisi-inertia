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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        $now = Carbon\Carbon::now();
        $resources = ['role', 'status', 'permission', 'user'];
        $actions = ['create', 'view', 'update', 'delete'];

        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => ucfirst($action).' '.ucfirst($resource).'s',
                    'slug' => $action.'-'.$resource.'s',
                ];
            }
        }

        $specialPermissions = [
            ['name' => 'Assign Role', 'slug' => 'assign-role'],
            ['name' => 'Assign Permission', 'slug' => 'assign-permission'],
        ];

        $permissions = array_merge($permissions, $specialPermissions);

        foreach ($permissions as $permission) {
            \App\Models\Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']],
                ['created_at' => $now]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
