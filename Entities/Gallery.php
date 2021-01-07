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
    	'locale',
    	'title',
    	'slug',
        'folder_id'
    ];

    public function files()
    {
        return $this->belongsToMany(
            'Modules\Media\Entities\File',
            'gallery__media_files',
            'gallery_id',
            'file_id'
        )->withPivot('order')->orderBy('order', 'asc');
    }

    public function folder()
    {
        return $this->hasOne(
            'Modules\Media\Entities\File',
            'id',
            'folder_id'
        );
    }
}
