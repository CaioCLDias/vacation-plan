<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HolidayPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =[
        'id', 'title', 'description', 'date', 'location', 'participants'
    ];

    protected $casts = [
        'participants' => 'array'
    ];

    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at'
    ];
}
