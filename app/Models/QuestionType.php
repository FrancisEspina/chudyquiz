<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $table = 'question_types';

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
