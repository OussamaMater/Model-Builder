<?php

use App\Enums\ModelStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('i_a_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->float('ration')->default('2');
            $table->integer('batch_size')->default(32);
            $table->boolean('shuffle')->default(true);
            $table->integer('training_epochs')->default(5);
            $table->string('status')->default(ModelStatus::SUBMITTED);
            $table->foreignId('dataset_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_a_models');
    }
};
