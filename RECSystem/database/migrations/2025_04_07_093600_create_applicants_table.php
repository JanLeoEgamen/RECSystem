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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex');
            $table->date('birthdate');
            $table->string('civil_status');
            $table->string('citizenship');
            $table->string('blood_type')->nullable();
            $table->string('cellphone_no');
            $table->string('telephone_no')->nullable();
            $table->string('email_address');
            $table->string('emergency_contact');
            $table->string('emergency_contact_number');
            $table->string('relationship');
            $table->string('licence_class')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_expiration_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
            
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
