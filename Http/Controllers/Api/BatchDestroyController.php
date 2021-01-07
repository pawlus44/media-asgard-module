<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Validators\FolderIsAssignedWithGallery;

class BatchDestroyController extends Controller
{
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var FolderRepository
     */
    private $folder;
    /**
     * @var Imagy
     */
    private $imagy;

    /**
     * @var File[]
     */
    private $listOfFileAndGalleryNotDestroy = [];

    /**
     * @var FolderIsAssignedWithGallery
     */
    private $folderIsAssignedWithGallery;

    public function __construct(
        FileRepository $file,
        FolderRepository $folder,
        Imagy $imagy,
        FolderIsAssignedWithGallery $folderIsAssignedWithGallery
    ) {
        $this->file = $file;
        $this->folder = $folder;
        $this->imagy = $imagy;
        $this->folderIsAssignedWithGallery = $folderIsAssignedWithGallery;
    }

    public function __invoke(Request $request)
    {
        foreach ($request->get('files') as $file) {
            if ($file['is_folder'] === true) {
                $this->deleteFolder($file['id']);
                continue;
            }
            $this->deleteFile($file['id']);
        }

        if (count($this->listOfFileAndGalleryNotDestroy) === 0) {
            return response()->json([
                'errors' => false,
                'message' => trans('media::messages.selected items deleted'),
                'status' => 'ok'
            ]);
        }

        if (count($request->get('files')) === count($this->listOfFileAndGalleryNotDestroy)) {
            return response()->json([
                'errors' => true,
                'message' => trans('media::messages.selected items is not deleted'),
                'status' => 'error'
            ], 422);
        }

        /** @var File $file */
        $listOfNotDestroyedFile = '';
        foreach ($this->listOfFileAndGalleryNotDestroy as $file) {
            $listOfNotDestroyedFile .= ', ' . $file->filename;
        }
        $listOfNotDestroyedFile = substr($listOfNotDestroyedFile,1);

        return response()->json([
            'errors' => false,
            'message' => trans(
                'media::messages.not all selected items is deleted',
                ['list' => $listOfNotDestroyedFile]
            ).trans('media::messages.selected items is not deleted'),
            'status' => 'something is not ok'
        ]);
    }

    private function deleteFile($fileId)
    {
        $file = $this->file->find($fileId);

        if ($file === null) {
            return;
        }

        if( $file->galleries->count() !==0 ) {
            $this->listOfFileAndGalleryNotDestroy[] = $file;
        }

        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);
    }

    private function deleteFolder($folderId)
    {
        $folder = $this->folder->findFolder($folderId);
        if ($folder === null) {
            return;
        }

        if( !$this->folderIsAssignedWithGallery->check($folder) ) {
            $this->listOfFileAndGalleryNotDestroy[] = $folder;
            return;
        }

        $this->folder->destroy($folder);
    }
}
