<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionStatus extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(Question::class, 'status_id');
    }
}
