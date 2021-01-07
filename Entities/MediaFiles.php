<?php namespace Modules\Media\Entities;


use Illuminate\Database\Eloquent\Model;

class MediaFiles extends Model
{
    protected $table = 'gallery__media_files';
    public $incrementing = false;

    protected $fillable = [
        'gallery_id',
        'file_id',
        'order'
    ];


    public function files()
    {
        return $this->hasMany('Modules\Media\Entities\File', 'id', 'file_id');
    }
}