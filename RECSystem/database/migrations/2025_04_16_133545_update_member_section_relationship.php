<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['bureau_id']);
            $table->dropColumn('bureau_id');
    
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
        });
    }
    
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
    
            $table->foreignId('bureau_id')->nullable()->constrained()->onDelete('set null');
        });
    }
    
};
