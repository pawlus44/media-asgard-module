<?php namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;

class Galleriesables extends Model
{
    protected $table = 'galleriesables';
    public $timestamps = false;

    protected $fillable = [
    	'gallery_id',
    	'qalleriesables_id',
    	'galleriesables_type'
    ];
}
