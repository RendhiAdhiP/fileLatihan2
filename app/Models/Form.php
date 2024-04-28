<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $hidden = ['created_at','updated_at'];

    public function User(){
        return $this->belongsTo(User::class,'creator_id','id');
    }

    public function allowedDomain(){
        return $this->hasMany(AllowedDomain::class);
    }
    
    public function question(){
        return $this->hasMany(Question::class);
    }

    public function response(){
        return $this->belongsToMany(User::class, 'Response', 'form_id','user_id')->withPivot(['date']);
    }
}
