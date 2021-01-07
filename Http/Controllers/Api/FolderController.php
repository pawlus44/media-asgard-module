<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Http\Requests\CreateFolderRequest;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Validators\FolderIsAssignedWithGallery;

class FolderController extends Controller
{
    /**
     * @var FolderRepository
     */
    private $folder;

    /**
     * @var FolderIsAssignedWithGallery $folderIsAssignedWithGallery
     */
    private $folderIsAssignedWithGallery;

    public function __construct(FolderRepository $folder, FolderIsAssignedWithGallery $folderIsAssignedWithGallery)
    {
        $this->folder = $folder;
        $this->folderIsAssignedWithGallery = $folderIsAssignedWithGallery;
    }

    public function store(CreateFolderRequest $request)
    {
        $folder = $this->folder->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('media::folders.folder was created'),
            'data' => $folder,
        ]);
    }

    public function update(File $folder, CreateFolderRequest $request)
    {
        $folder = $this->folder->update($folder, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('media::folders.folder was updated'),
            'data' => $folder,
        ]);
    }

    public function destroy(File $folder)
    {
        if( !$this->folderIsAssignedWithGallery->check($folder) ) {
            return response()->json([
                'error' => true,
                'message' => trans('media::messages.folder contain item joined with gallery')
            ], 422);
        }

        $this->folder->destroy($folder);

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.folder deleted'),
        ]);
    }
}
