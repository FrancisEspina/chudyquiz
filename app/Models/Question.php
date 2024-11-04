<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Diff\Diff;

class Question extends Model
{
    protected $fillable = [
        'question',
        'qtype_id',
        'difficulty',
        'subject_id'
    ];

    protected $table = 'questions';

    public function choices()
    {
        return $this->hasMany(Item::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function difficultyLevel()
    {
        return $this->belongsTo(Difficulty::class, 'difficulty');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'qtype_id');
    }
    public function correct_answer()
    {
        return $this->hasOne(Answer::class);
    }
}
