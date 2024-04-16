<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanInventoriTable extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_inventori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->text('inventori_list');
            $table->string('dibuat_oleh');
            $table->enum('disetujui_oleh', ['direktur', 'manager operasional', 'manager territory', 'manager keuangan', 'area manager', 'kepala cabang', 'kepala gudang']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_inventori');
    }
}
