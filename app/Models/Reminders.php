<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    protected $fillable = ["task_id", "message", "value_time_reminder" ];
}
