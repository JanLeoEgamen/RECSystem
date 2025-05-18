<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['section_name', 'bureau_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function bureau()
    {
        return $this->belongsTo(Bureau::class);
    }


    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'user_bureau_section')
            ->withPivot('bureau_id');
    }
}
