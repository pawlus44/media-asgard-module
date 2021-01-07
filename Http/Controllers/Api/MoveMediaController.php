<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Http\Requests\MoveMediaRequest;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\Movers\Mover;
use Modules\Media\Validators\FolderIsAssignedWithGallery;

class MoveMediaController extends Controller
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
     * @var Mover
     */
    private $mover;

    /**
     * @var FolderIsAssignedWithGallery
     */
    private $folderIsAssignedWithGallery;

    /**
     * @var array
     */
    private $listOfFileNotMoved = [];

    /**
     * @var array
     */
    private $listOfFileNotMovedError = [];

    public function __construct(
        FileRepository $file,
        FolderRepository $folder,
        Mover $mover,
        FolderIsAssignedWithGallery $folderIsAssignedWithGallery
    ) {
        $this->file = $file;
        $this->folder = $folder;
        $this->mover = $mover;
        $this->folderIsAssignedWithGallery = $folderIsAssignedWithGallery;
    }

    public function __invoke(MoveMediaRequest $request)
    {
        $destination = $this->folder->findFolderOrRoot($request->get('destinationFolder'));

        foreach ($request->get('files') as $file) {
            $fileToMoves = $this->file->find($file['id']);
            if( $fileToMoves->isFolder() ) {
                if( !$this->folderIsAssignedWithGallery->check($fileToMoves) ) {
                    $this->listOfFileNotMoved[] = $fileToMoves;
                    continue;
                }
            }

            if( $fileToMoves->galleries->count() !==0 ) {
                $this->listOfFileNotMoved[] = $file;
                continue;
            }
            //$failedMoves = $this->mover->move($fileToMoves, $destination);

/*            if ($failedMoves == 0) {
                $this->listOfFileNotMovedError[] = $fileToMoves;
            }*/
        }

        if( count($this->listOfFileNotMovedError) === 0 && count($this->listOfFileNotMoved) !== 0 ) {
            $listOfNotMovedFile = '';
            foreach ($this->listOfFileNotMoved as $file) {
                $listOfNotMovedFile .= ', '.$listOfNotMovedFile;
            }
            $listOfNotMovedFile = substr($listOfNotMovedFile, 1);

            return response()->json([
                'errors' => false,
                'message' => trans
                    (
                        'media::media.not all selected items is moved',
                        ['list' => $listOfNotMovedFile]
                    )
                    .trans('media::media.some files not moved'),
                'status' => 'something is not ok'
            ]);
        }

        if( count($request->get('files')) === count($this->listOfFileNotMoved) ) {
            return response()->json([
                'errors' => true,
                'message' => trans('media::media.some files not moved all files is attach to gallery'),
                'status' => 'error'
            ], 422);
        }

        if( count($this->listOfFileNotMovedError) !== 0 || count($this->listOfFileNotMoved) !== 0 ) {
            return response()->json([
                'errors' => true,
                'message' => trans('media::media.some files not moved'),
                'status' => 'error'
            ], 422);
        }

        if( count($this->listOfFileNotMovedError) !== 0 || count($this->listOfFileNotMoved) !== 0 ) {
            return response()->json([
                'errors' => true,
                'message' => trans('media::media.some files not moved'),
                'status' => 'error'
            ], 422);
        }

        return response()->json([
            'errors' => false,
            'message' => trans('media::media.files moved successfully'),
            'status' => 'ok'
        ]);
    }
}
