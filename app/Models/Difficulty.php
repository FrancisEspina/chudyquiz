<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $table = 'difficulty';
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
