<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'type_id', 'active', 'sort'];

    public static $upload_dir = 'storage/app/public/images';

    /**
     *   Upload directory for images
     */
    public static $dir = 'app/public/images/';

    /**
     * Relationship
     */
    public function imageLang()
    {
        return $this->hasMany(ImageLang::class, 'type_id', 'id');
    }
}
