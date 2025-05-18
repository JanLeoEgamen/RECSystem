<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ApplicantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Data options
        $statuses = ['Pending', 'Approved', 'Rejected'];
        $sexes = ['Male', 'Female'];
        $civilStatuses = ['Single', 'Married', 'Widowed', 'Separated'];
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $licenseClasses = ['A', 'B', 'C', 'D', 'E'];
        $relationships = ['Father', 'Mother', 'Spouse', 'Sibling', 'Friend'];
        
        // Philippine-specific data
        $regions = ['NCR', 'Region I', 'Region II', 'Region III', 'Region IV-A', 'Region V'];
        $provinces = [
            'Metro Manila', 'Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan',
            'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Bataan', 'Bulacan',
            'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales', 'Batangas',
            'Cavite', 'Laguna', 'Quezon', 'Rizal'
        ];

        // Create a user if none exists
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password')
            ]);
        }

        for ($i = 0; $i < 50; $i++) {
            $birthdate = $faker->dateTimeBetween('-60 years', '-18 years');
            $hasLicense = $faker->boolean(70); // 70% chance of having license
            
            Applicant::create([
                'status' => $faker->randomElement($statuses),
                'last_name' => $faker->lastName,
                'first_name' => $faker->firstName,
                'middle_name' => $faker->firstName, // Always provide middle name
                'suffix' => $faker->randomElement(['', 'Jr.', 'Sr.', 'II', 'III', 'IV']), // Empty string instead of null
                'sex' => $faker->randomElement($sexes),
                'birthdate' => $birthdate,
                'civil_status' => $faker->randomElement($civilStatuses),
                'citizenship' => 'Filipino',
                'blood_type' => $faker->randomElement($bloodTypes),
                'cellphone_no' => '09' . $faker->numerify('#########'),
                'telephone_no' => $faker->numerify('########'), // Always provide telephone
                'email_address' => $faker->unique()->safeEmail,
                'emergency_contact' => $faker->name,
                'emergency_contact_number' => '09' . $faker->numerify('#########'),
                'relationship' => $faker->randomElement($relationships),
                'licence_class' => $hasLicense ? $faker->randomElement($licenseClasses) : 'N/A',
                'license_number' => $hasLicense ? $faker->bothify('??#########') : 'N/A',
                'license_expiration_date' => $hasLicense ? $faker->dateTimeBetween('now', '+5 years') : now()->addYear(),
                'user_id' => $user->id,
                'region' => $faker->randomElement($regions),
                'province' => $faker->randomElement($provinces),
                'municipality' => $faker->city,
                'barangay' => 'Brgy. ' . $faker->streetName,
                'house_building_number_name' => $faker->buildingNumber,
                'street_address' => $faker->streetAddress,
                'zip_code' => $faker->postcode,
                'is_student' => $faker->boolean,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}