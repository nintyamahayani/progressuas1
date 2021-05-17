<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'harga', 'keterangan', 'image', 'kategori'];
    protected $appends = ['status_label'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class ="text-pink-500">Tas</span>';
        }
        return '<span class ="text-blue-500">Dompet</span>'; {
            return '<span class ="text-purple-500">Jam Tangan</span>';
        }
    }
}
