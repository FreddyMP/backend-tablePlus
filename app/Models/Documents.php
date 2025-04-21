<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $fillable = ["task_id","url","deleted_at"];

    
}
