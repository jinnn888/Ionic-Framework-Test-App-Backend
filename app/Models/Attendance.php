<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];

    public function getTimeInAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('H:i A');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

}
