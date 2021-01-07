<?php namespace Modules\Media\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Media\Entities\File;
use Modules\Media\Entities\Gallery;
use Modules\Media\Entities\MediaFiles;

interface GalleryRepository extends BaseRepository
{
    /**
     * Method attach file to gallery
     *
     * @param Gallery $gallery
     * @param File $file
     * @return MediaFiles
     */
	public function assignImagesToGallery(Gallery $gallery, File $file, int $order): MediaFiles;
}
