<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // protected $guraded = [];
    protected $fillable = [
        'name', 
        'type', 
        'is_required', 
        'choices', 
        'form_id',
    ];

    public function answer(){
        return $this->belongsToMany(Response::class, 'answers', 'response_id','question_id')->withPivot(['value']);
    }

    public function from(){
        return $this->belongsTo(Form::class);
    }
}
