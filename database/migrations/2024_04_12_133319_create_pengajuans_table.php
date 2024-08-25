<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_referensi')->unique();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->unsignedBigInteger('diketahui_oleh')->nullable();
            $table->enum('setujui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->enum('ketahui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->string('alasan');
            $table->string('bukti_nota');
            $table->timestamps();

            // Menambahkan foreign key constraints
            $table->foreign('dibuat_oleh')->references('id')->on('users');
            $table->foreign('disetujui_oleh')->references('id')->on('users');
            $table->foreign('diketahui_oleh')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuans');
    }
}
