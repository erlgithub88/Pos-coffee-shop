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
    Schema::create('item_supplies', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('manager_id');
      $table->unsignedBigInteger('item_id');
      $table->integer('qty');
      $table->date('date')->useCurrent();
      $table->timestamps();

      $table->foreign('manager_id')->references('id')->on('managers');
      $table->foreign('item_id')->references('id')->on('items');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('item_supplies');
  }
};
