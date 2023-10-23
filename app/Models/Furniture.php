<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'asset_id',
        'material',
        'furniture_type',
       
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id','id');
    }
}
