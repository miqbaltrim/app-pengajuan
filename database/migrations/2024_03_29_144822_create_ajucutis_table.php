<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjucutisTable extends Migration
{
    public function up()
    {
        Schema::create('ajucutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->date('mulai_cuti');
            $table->date('selesai_cuti');
            $table->text('alasan');
            $table->enum('status', ['tunggu', 'disetujui', 'ditolak', 'selesai'])->default('tunggu');
            $table->enum('approved', ['admin', 'direktur', 'manager-operasional', 'manager-territory', 'manager-keuangan', 'area-manager', 'kepala-cabang', 'kepala-gudang', 'staff-office', 'gudang'])->nullable();
            $table->integer('jml_cuti')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ajucutis');
    }
}


