<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password')->default(bcrypt('user123'));
            $table->enum('role', ['admin', 'direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang', 'staff-office', 'gudang']);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('position')->nullable();
            $table->string('photo')->nullable();
            $table->integer('jml_cuti')->default(12);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
