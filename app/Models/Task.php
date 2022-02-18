<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'completed_at',
        'board_id'
    ];

    protected $dates = ['completed_at'];
    protected $appends = [
        'is_complete'
    ];

    public function getIsCompleteAttribute()
    {
        return ($this->completed_at !== null);
    }

}
