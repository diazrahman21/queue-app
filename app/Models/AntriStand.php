<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AntriStand extends Model
{
    use HasFactory;

    protected $table = 'tbl_antri_stand';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'tanggal_pesan',
        'kd_stand',
        'nomor_antri',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
    ];

    public function stand()
    {
        return $this->belongsTo(QuotaStand::class, 'kd_stand', 'kd_stand');
    }
}
