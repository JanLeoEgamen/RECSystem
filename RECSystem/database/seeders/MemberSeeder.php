<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Applicant;
use App\Models\MembershipType;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create required dependencies if they don't exist
        $this->createDependencies();

        // Get existing data
        $membershipTypes = MembershipType::all();
        $sections = Section::all();
        $applicants = Applicant::all();
        $users = User::all();

        // Data configuration
        $statuses = ['Active', 'Inactive'];
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

        for ($i = 0; $i < 50; $i++) {
            $birthdate = $faker->dateTimeBetween('-60 years', '-18 years');
            $isLifetime = $faker->boolean(20); // 20% chance of lifetime membership
            $membershipStart = $faker->dateTimeBetween('-5 years', 'now');
            $hasLicense = $faker->boolean(70); // 70% chance of having license
            
            $memberData = [
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
                'rec_number' => 'MEM-' . now()->format('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'licence_class' => $hasLicense ? $faker->randomElement($licenseClasses) : 'N/A',
                'license_number' => $hasLicense ? $faker->bothify('??#########') : 'N/A',
                'license_expiration_date' => $hasLicense ? $faker->dateTimeBetween('now', '+5 years') : now()->addYear(),
                'applicant_id' => $applicants->isEmpty() ? 0 : $applicants->random()->id, // Default to 0 if no applicants
                'section_id' => $sections->random()->id,
                'membership_type_id' => $membershipTypes->random()->id,
                'user_id' => $users->random()->id,
                'region' => $faker->randomElement($regions),
                'province' => $faker->randomElement($provinces),
                'municipality' => $faker->city,
                'barangay' => 'Brgy. ' . $faker->streetName,
                'house_building_number_name' => $faker->buildingNumber,
                'street_address' => $faker->streetAddress,
                'zip_code' => $faker->postcode,
                'membership_start' => $membershipStart,
                'is_lifetime_member' => $isLifetime,
                'last_renewal_date' => $isLifetime ? now() : $faker->dateTimeBetween($membershipStart, 'now'),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => Carbon::now(),
            ];

            // Set membership end date if not lifetime member
            if (!$isLifetime) {
                $memberData['membership_end'] = $faker->dateTimeBetween(
                    $membershipStart, 
                    $faker->dateTimeBetween('now', '+5 years')
                );
            } else {
                $memberData['membership_end'] = null; // Explicitly set to null for lifetime members
            }

            Member::create($memberData);
        }
    }

    protected function createDependencies()
    {
        // Create membership types if none exist
        if (MembershipType::count() === 0) {
            MembershipType::insert([
                ['name' => 'Regular', 'description' => 'Regular membership', 'price' => 1000],
                ['name' => 'Associate', 'description' => 'Associate membership', 'price' => 500],
                ['name' => 'Lifetime', 'description' => 'Lifetime membership', 'price' => 10000],
            ]);
        }

        // Create sections if none exist
        if (Section::count() === 0) {
            Section::insert([
                ['name' => 'North Division', 'description' => 'Northern region division'],
                ['name' => 'South Division', 'description' => 'Southern region division'],
                ['name' => 'East Division', 'description' => 'Eastern region division'],
                ['name' => 'West Division', 'description' => 'Western region division'],
                ['name' => 'Metro Division', 'description' => 'Metro area division'],
            ]);
        }

        // Create at least one user if none exist
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
        }

        // Create some applicants if none exist
        if (Applicant::count() === 0) {
            Applicant::factory()->count(20)->create();
        }
    }
}