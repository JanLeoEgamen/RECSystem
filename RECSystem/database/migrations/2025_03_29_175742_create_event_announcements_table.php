<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->date('event_date');
            $table->integer('year');
            $table->text('caption');
            $table->string('image');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_announcements');
    }
};
