<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'email',
        'phone',
        'section_id',
        'office_id',
        
    ];
    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id','id');
    }
}
