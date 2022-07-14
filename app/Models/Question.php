<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'detail',
        'status_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function state()
    {
        return $this->hasOne(QuestionState::class);
    }

    public function status()
    {
        return $this->hasOne(QuestionStatus::class, 'id', 'status_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'question_like', 'question_id', 'user_id');
    }
}
