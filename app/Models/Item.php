<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'question_id',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',

    ];
    protected $table = 'items';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
