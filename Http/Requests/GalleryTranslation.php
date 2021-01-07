<?php namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'gallery_id',
    	'locale',
    	'title',
    	'slug'
    ];
    
    protected $table = 'gallery__gallery_translations';
}
