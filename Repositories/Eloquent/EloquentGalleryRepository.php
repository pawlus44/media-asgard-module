<?php namespace Modules\Media\Repositories\Eloquent;

use Modules\Media\Entities\File;
use Modules\Media\Entities\Gallery;
use Modules\Media\Entities\MediaFiles;
use Modules\Media\Repositories\GalleryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentGalleryRepository extends EloquentBaseRepository implements GalleryRepository
{
    public function assignImagesToGallery(Gallery $gallery, File $file, int $order): MediaFiles
    {
        $mediaFiles = new MediaFiles();
        $mediaFiles->gallery_id = $gallery->id;
        $mediaFiles->file_id = $file->id;
        $mediaFiles->order = $order;
        $mediaFiles->save();

        return $mediaFiles;
    }
}
