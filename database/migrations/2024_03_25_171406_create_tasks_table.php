<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

        public function up()
        {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                // Otros campos que necesites para tu tarea
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('tasks');
        }

};
