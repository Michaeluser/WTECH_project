<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique();
            $table->string('description')->nullable();
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();

            $table->primary(['user_id', 'role_id']);
        });

        DB::table('roles')->insert([
            [
                'role' => 'admin',
                'description' => 'Administrator with access to the admin dashboard.',
            ],
            [
                'role' => 'customer',
                'description' => 'Regular customer account.',
            ],
        ]);

        $adminRoleId = DB::table('roles')->where('role', 'admin')->value('id');
        $customerRoleId = DB::table('roles')->where('role', 'customer')->value('id');

        $users = DB::table('users')->select('id', 'is_staff')->get();

        foreach ($users as $user) {
            DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role_id' => $customerRoleId,
            ]);

            if ($user->is_staff && $adminRoleId !== null) {
                DB::table('user_role')->insert([
                    'user_id' => $user->id,
                    'role_id' => $adminRoleId,
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_staff');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_staff')->default(false)->after('swift_card');
        });

        $adminRoleId = DB::table('roles')->where('role', 'admin')->value('id');

        if ($adminRoleId !== null) {
            $adminUserIds = DB::table('user_role')
                ->where('role_id', $adminRoleId)
                ->pluck('user_id');

            if ($adminUserIds->isNotEmpty()) {
                DB::table('users')
                    ->whereIn('id', $adminUserIds)
                    ->update(['is_staff' => true]);
            }
        }

        Schema::dropIfExists('user_role');
        Schema::dropIfExists('roles');
    }
};
