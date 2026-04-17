<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotaStand extends Model
{
    use HasFactory;

    protected $table = 'tbl_quota_stand';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kd_stand',
        'nama_stand',
        'kuota',
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    public function bookings()
    {
        return $this->hasMany(AntriStand::class, 'kd_stand', 'kd_stand');
    }
}
