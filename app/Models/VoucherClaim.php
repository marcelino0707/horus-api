<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_voucher',
        'id_user',
        'tanggal_claim',
    ];
}
