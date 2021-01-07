<?php

declare(strict_types=1);

namespace Modules\Media\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Media\Entities\MediaFiles;
use Modules\Media\Repositories\GalleryMediaFilesRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentGalleryMediaFilesRepository extends EloquentBaseRepository implements GalleryMediaFilesRepository
{
    public function getLastItemInOrderFilesList(int $galleryId)
    {
        return $this->getByAttributes(
            ['gallery_id' => $galleryId],
            'order',
            'desc'
        )->first();
    }

    public function getItemByFileIdAndGalleryId(int $fileId, int $galleryId): MediaFiles
    {
        return $this->getByAttributes(['gallery_id' => $galleryId, 'file_id' => $fileId])->first();
    }

    public function reduceFileOrderNumber(int $startOrderNumber, int $endOrderNumber, int $galleryId)
    {
        $listFiles = $this->model->query()
            ->where('order','>=', $startOrderNumber)
            ->where('order','<=', $endOrderNumber)
            ->where('gallery_id','=',$galleryId)
            ->get();

        foreach ($listFiles as $file) {
            $currentOrder = $file->order;
            DB::table('gallery__media_files')
                ->where('gallery_id', $file->gallery_id)
                ->where('file_id', $file->file_id)
                ->update(['order' => $currentOrder-1]);
        }
    }

    public function increaseFileOrderNumber(int $startOrderNumber, int $endOrderNumber, int $galleryId)
    {
        $listFiles = $this->model->query()
            ->where('order','>=', $startOrderNumber)
            ->where('order','<=', $endOrderNumber)
            ->where('gallery_id','=',$galleryId)
            ->get();

        foreach ($listFiles as $file) {
            $currentOrder = $file->order;
            DB::table('gallery__media_files')
                ->where('gallery_id', $file->gallery_id)
                ->where('file_id', $file->file_id)
                ->update(['order' => $currentOrder+1]);
        }
    }

    /**
     * @param int $galleryId
     * @param int $fileId
     * @param int $order
     */
    public function detachFileFromGallery(int $galleryId, int $fileId, int $order)
    {
        DB::table('gallery__media_files')
            ->where('gallery_id', $galleryId)
            ->where('file_id', $fileId)
            ->where('order', $order)
            ->delete();
    }
}