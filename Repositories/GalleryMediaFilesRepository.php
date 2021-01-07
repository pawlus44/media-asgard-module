<?php

declare(strict_types=1);

namespace Modules\Media\Repositories;

use Modules\Media\Entities\MediaFiles;

/**
 * Repository to retrieve file attached to gallery
 *
 * Interface GalleryMediaFilesRepository
 * @package Modules\Media\Repositories
 */
interface GalleryMediaFilesRepository
{
    /**
     * @param int $galleryId
     * @return MediaFiles|null
     */
    public function getLastItemInOrderFilesList(int $galleryId);

    /**
     * Methode to get media files by gallery id and file id
     *
     * @param int $fileId
     * @param int $galleryId
     * @return MediaFiles
     */
    public function getItemByFileIdAndGalleryId(int $fileId, int $galleryId): MediaFiles;

    /**
     * @param int $startOrderNumber
     * @param int $endOrderNumber
     * @param int $galleryId
     * @return mixed
     */
    public function reduceFileOrderNumber(int $startOrderNumber, int $endOrderNumber, int $galleryId);

    /**
     * @param int $startOrderNumber
     * @param int $endOrderNumber
     * @param int $galleryId
     * @return mixed
     */
    public function increaseFileOrderNumber(int $startOrderNumber, int $endOrderNumber, int $galleryId);

    /**
     * @param int $galleryId
     * @param int $fileId
     * @param int $order
     */
    public function detachFileFromGallery(int $galleryId, int $fileId, int $order);
}