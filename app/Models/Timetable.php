<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $guarded = []; // Allow mass assignment
    public $timestamps = true;

    // Dynamically set table name
    public function setDay($day)
    {
        $this->setTable(strtolower($day));
        return $this;
    }
}
