<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles,  SoftDeletes; // Add SoftDeletes trait
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    
    public function communities()
    {
        return $this->hasMany(Community::class);
    }
    
    public function eventAnnouncements()
    {
        return $this->hasMany(EventAnnouncement::class);
    }
    
    public function faqs()
    {
        return $this->hasMany(FAQ::class);
    }
    
    public function mainCarousels()
    {
        return $this->hasMany(MainCarousel::class);
    }


    public function supporters()
    {
        return $this->hasMany(Supporter::class);
    }

    public function bureaus()
    {
        return $this->hasMany(Bureau::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function membershipTypes()
    {
        return $this->hasMany(MembershipType::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }


    public function assignedBureaus()
    {
        return $this->belongsToMany(Bureau::class, 'user_bureau_section')
            ->withPivot('section_id');
    }

    public function assignedSections()
    {
        return $this->belongsToMany(Section::class, 'user_bureau_section')
            ->withPivot('bureau_id');
    }

}
