<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatCutiTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_cuti')->constrained('ajucutis');
            $table->date('tanggal')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_cuti');
    }
}
