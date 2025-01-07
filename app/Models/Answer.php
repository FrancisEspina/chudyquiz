<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'answer',
        'reason'
    ];

    protected $table = 'answers';

    public function question()
    {
        return $this->belongs(Question::class, 'question_id');
    }
}
