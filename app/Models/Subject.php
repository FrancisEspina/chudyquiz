<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name'
    ];
    protected $table = 'subjects';

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
