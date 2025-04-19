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
        Schema::table('members', function (Blueprint $table) {
            $table->date('membership_start')->nullable()->after('license_expiration_date');
            $table->date('membership_end')->nullable()->after('membership_start');
            $table->boolean('is_lifetime_member')->default(false)->after('membership_end');
            $table->date('last_renewal_date')->nullable()->after('is_lifetime_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
