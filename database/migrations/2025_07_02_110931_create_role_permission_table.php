<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * ADMIN ID
     */
    private const WATCHER = 1;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Role::class)->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Permission::class)->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        $permissions = Permission::all();

        $permissions->each(function ($permission) {
            $permission->roles()->attach(self::WATCHER);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};
