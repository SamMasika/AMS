<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electronic extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'asset_id',
        'modeli',
        'chapa',
        'serial_no',
        'computer_type',
        'phone_type',
        'size',
        'disk_size',
        'monitor_size',
        'accessories',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id','id');
    }
}
