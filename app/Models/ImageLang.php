<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageLang extends Model
{
    use HasFactory;

    public $timestamps = FALSE;
    protected $table = 'image_langs';
    protected $fillable = ['lang', 'alt', 'title', 'real_name'];

    /**
     * Relationship
     */
    public function image()
    {
        return $this->belongsTo(Image::class, 'type_id', 'id');
    }
}
