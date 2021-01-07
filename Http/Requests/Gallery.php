<?php namespace Modules\Media\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use Translatable;

    protected $table = 'gallery__galleries';
    public $translatedAttributes = [
        'gallery_id',
    	'locale',
    	'title',
    	'slug'
    ];
    
    protected $fillable = [
    	'gallery_id',
    	'status',
    	'type',
    	'locale',
    	'title',
    	'slug'
    ];



    public function files()
    {
        return $this->belongsToMany('Modules\Media\Entities\File','gallery__media_files','gallery_id','file_id');
    }

}
