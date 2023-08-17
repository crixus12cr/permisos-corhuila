<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');

            $table->foreignId('type_document_id')
            ->constrained('type_documents')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->string('document_number');

            $table->foreignId('position_id')
            ->constrained('positions')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('area_id')
            ->constrained('areas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('rol_id')
            ->default(3)
            ->constrained('rols')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
};
