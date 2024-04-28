<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedDomain extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function creator(){
        return $this->belongsTo(User::class,'creator_id','id');
    }

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function answer(){
        return $this->belongsToMany(Response::class, 'answer', 'question_id','response_id')->withPivot(['value']);
    }
}
