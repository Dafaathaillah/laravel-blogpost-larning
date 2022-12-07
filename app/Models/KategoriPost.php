<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class KategoriPost extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $guarded = [
        'created_at',
        'updated_at',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_kategori = Uuid::uuid4()->toString();
        });
    }
    public function produk()
    {
        return $this->hasMany(Post::class, 'kategori_id', 'id_kategori');
    }
}
