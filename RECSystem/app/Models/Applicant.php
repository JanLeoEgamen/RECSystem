<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
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
        'licence_class',
        'license_number',
        'license_expiration_date',
        'user_id',
        'region',
        'province',
        'municipality',
        'barangay',
        'house_building_number_name',
        'zip_code',
        'street_address',
        'is_student'
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

}
