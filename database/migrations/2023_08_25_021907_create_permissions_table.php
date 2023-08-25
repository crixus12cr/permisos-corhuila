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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            $table->date('request_date');
            $table->integer('day');
            $table->string('start_hour');
            $table->string('end_hour');
            $table->string('commitment');
            $table->text('observations')->nullable();

            $table->boolean('autorization_user')->nullable();
            $table->boolean('autorization_boss')->nullable();
            $table->boolean('autorization_hr')->nullable();

            $table->foreignId('user_id')
            ->constrained('users')
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
        Schema::dropIfExists('permissions');
    }
};
