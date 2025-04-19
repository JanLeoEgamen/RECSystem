<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bureau extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['bureau_name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function members()
    {
        return $this->hasManyThrough(Member::class, Section::class);
    }


}
