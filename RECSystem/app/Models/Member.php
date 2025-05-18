<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'sex',
        'birthdate',
        'civil_status',
        'citizenship',
        'blood_type',
        'cellphone_no',
        'telephone_no',
        'email_address',
        'emergency_contact',
        'emergency_contact_number',
        'relationship',
        'rec_number',
        'licence_class',
        'license_number',
        'license_expiration_date',
        'applicant_id',
        'section_id',
        'membership_type_id',
        'user_id',
        'region',
        'province',
        'municipality',
        'barangay',
        'street_address',
        'house_building_number_name',
        'zip_code',
        'membership_start',
        'membership_end',
        'is_lifetime_member',
        'last_renewal_date',


    ];

// In your Member model
protected $dates = [
    'birthdate',
    'license_expiration_date',
    'membership_end', // Add this line
    'membership_start', // You might want to add this too
    'last_renewal_date', // And this if needed
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function bureau()
    {
        return $this->hasOneThrough(Bureau::class, Section::class);
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

}
