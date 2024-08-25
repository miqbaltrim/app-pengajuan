<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasbonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key terhubung dengan tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('keterangan');
            $table->decimal('jml_kasbon', 20, 2);
            $table->integer('cicilan');
            $table->decimal('sisa_cicilan', 20, 2);
            $table->enum('dana_dari', ['CBA', 'CBM', 'CBS', 'CBU', 'ABJ', 'DANWIL'])->nullable();
            
            $table->unsignedBigInteger('disetujui_oleh'); // Foreign key terhubung dengan tabel users
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('diketahui_oleh'); // Foreign key terhubung dengan tabel users
            $table->foreign('diketahui_oleh')->references('id')->on('users')->onDelete('cascade');
            
            $table->enum('setujui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->enum('ketahui', ['tunggu', 'diterima', 'ditolak'])->default('tunggu');
            $table->enum('status_cicilan', ['belum lunas', 'lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kasbons');
    }
}

