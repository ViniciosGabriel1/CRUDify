<?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration {
                public function up()
                {
                    Schema::create('Tanisha Wilder', function (Blueprint $table) {
                        $table->id();
                        $table->text('Aspernatur minus sit');
            $table->timestamp('Aspernatur aut dolor');
            $table->timestamp('Explicabo Elit nis');
            $table->integer('Esse autem molestias');
            $table->integer('Aliqua Nulla sed qu');
            $table->boolean('Quae saepe saepe ad');
            $table->boolean('Nobis nulla et exerc');
            
                        $table->timestamps();
                    });
                }

                public function down()
                {
                    Schema::dropIfExists('Tanisha Wilder');
                }
            };
            