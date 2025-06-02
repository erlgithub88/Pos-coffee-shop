<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('cashflows', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('manager_id');
      $table->string('title', 50);
      $table->text('desc');
      $table->decimal('nominal', 15, 2);
      $table->enum('type', ['income', 'expense']);
      $table->date('date')->useCurrent();
      $table->timestamps();

      $table->foreign('manager_id')->references('id')->on('managers');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cashflows');
  }
};
