<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'description',
        'date_end',
        'hour_end',
        'status',
        'user_id'
    ];

    public function documents(){
        return $this->hasMany(documents::class, 'task_id');
    }
}
