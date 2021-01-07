<?php

declare(strict_types=1);

namespace Modules\Media\Validators;

use Modules\Media\Entities\File;
use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Services\FolderContentService;

class FolderIsAssignedWithGallery
{
    /**
     * @var FolderContentService
     */
    private $folderContentService;

    /**
     * CheckIfFolderCanBeRemovedService constructor.
     * @param FolderContentService $folderContentService
     */
    public function __construct(FolderContentService $folderContentService)
    {
        $this->folderContentService = $folderContentService;
    }

    public function check(File $folder) : bool
    {
        if ( !is_null($folder->galleryFolder)) {
            return false;
        }

        /** @var Collection $folderContents */
        $folderContents = $this->folderContentService->getFolderContentList($folder);

        /** @var File $folder */
        foreach ($folderContents->get('folders') as $folder) {
            if ( !is_null($folder->galleryFolder)) {
                return false;
            }
        }

        /** @var File $file */
        foreach ($folderContents->get('files') as $file) {
            if ($file->galleries->count() !== 0) {
                return false;
            }
        }

        return true;
    }

}