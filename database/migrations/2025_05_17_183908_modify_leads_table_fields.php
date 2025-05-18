<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Удаляем ненужные поля
            if (Schema::hasColumn('leads', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('leads', 'email')) {
                $table->dropColumn('email');
            }

            // Добавляем нужные поля
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('assigned_to')->nullable();

            // Добавляем внешний ключ (если надо)
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Восстанавливаем удаленные поля
            $table->string('name')->nullable();
            $table->string('email')->nullable();

            // Удаляем добавленные поля
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['description', 'manager_id', 'assigned_to']);
        });
    }
};
