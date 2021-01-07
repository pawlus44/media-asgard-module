<?php

declare(strict_types=1);

namespace Modules\Media\Services;

use Modules\Media\Entities\File;
use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Repositories\FolderRepository;

class FolderContentService
{
    /**
     * @var FolderRepository
     */
    private $folderRepository;

    /**
     * @var File[]
     */
    private $listFilesIntoFolder;

    /**
     * @var File[]
     */
    private $listFoldersIntoFolder;

    /**
     * FolderContentService constructor.
     * @param FolderRepository $folderRepository
     */
    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepository = $folderRepository;
        $this->listFilesIntoFolder = [];
        $this->listFoldersIntoFolder = [];
    }

    public function getFolderContentList(File $folder) : Collection
    {
        $listOfContentFolder = $this->folderRepository->getListOfContentFolder($folder);
        $listOfDependencyFolderToGetContent = [];
        foreach ($listOfContentFolder as $file) {
            if ($file->is_folder) {
                $this->addFolderToList($file);
                $listOfDependencyFolderToGetContent[] = $file;
                continue;
            }
            $this->addFileToList($file);
        }

        while (!empty($listOfDependencyFolderToGetContent)) {
            $tmpListOfDependencyFolder = $listOfDependencyFolderToGetContent;
            $listOfDependencyFolderToGetContent = [];

            foreach ($tmpListOfDependencyFolder as $folder) {
                $listContent = $this->folderRepository->getListOfContentFolder($folder);
                foreach ($listContent as $file) {
                    if ($file->is_folder) {
                        $this->addFolderToList($file);
                        $listOfDependencyFolderToGetContent[] = $file;
                        continue;
                    }
                    $this->addFileToList($file);
                }
            }
        }

        return new Collection(
            [
                'allFiles' => array_merge($this->listFoldersIntoFolder,$this->listFilesIntoFolder),
                'folders' =>  $this->listFoldersIntoFolder,
                'files' => $this->listFilesIntoFolder
            ]
        );
    }

    /**
     * @param File $file
     */
    private function addFileToList(File $file)
    {
        $this->listFilesIntoFolder[] = $file;
    }

    /**
     * @param File $file
     */
    private function addFolderToList(File $file)
    {
        $this->listFoldersIntoFolder[] = $file;
    }
}