<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzinsTable extends Migration
{
    public function up()
    {
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->date('mulai_izin');
            $table->date('selesai_izin');
            $table->text('alasan');
            $table->unsignedBigInteger('disetujui_oleh');
            $table->unsignedBigInteger('diketahui_oleh');
            $table->unsignedBigInteger('dibuat_oleh');
            $table->enum('setujui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->enum('ketahui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diketahui_oleh')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('izins');
    }
}
