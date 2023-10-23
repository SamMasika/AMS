<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'office_id',
        'section_id',
        'user_id',
        'purchase_date',
        'purchasing_price',
        'category',
        'subcategory',
        'asset_tag',
        'condition',
    ];
    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id','id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class,'staff_id','id');
    }
    public function electronic()
    {
        return $this->hasOne(Electronic::class, 'asset_id');
    }
    public function furniture()
    {
        return $this->hasOne(Furniture::class, 'asset_id');
    }
    
}
