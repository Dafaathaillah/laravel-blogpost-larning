<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Post extends Model
{
    use HasFactory, Sluggable;
    protected $primaryKey = 'id_post';
    public $incrementing = false;
    protected $guarded = [
        'created_at',
        'updated_at',
    ]; //guarded pengecualian masukan data

    protected static function boot(){
        parent::boot();
    
        static::creating(function ($model) {
            $model->id_post = Uuid::uuid4()->toString();

        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPost::class, 'kategori_id', 'id_kategori');
    }

    public function usr()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function sluggable(): array
    {
        return [
            'slug_post' => [
                'source' => 'judul_post',
            ],
        ];
    }
}
