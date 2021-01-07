<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Media\Image\Imagy;
use Modules\Media\Entities\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Media\Entities\Gallery;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Entities\MediaFiles;
use Modules\Media\Events\FileWasLinked;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Yajra\DataTables\Facades\DataTables;
use Modules\Media\Events\FileWasUnlinked;
use Modules\Media\Events\FileWasUploaded;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Transformers\MediaTransformer;
use Modules\Media\Repositories\GalleryRepository;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Repositories\GalleryMediaFilesRepository;
use Modules\Media\Http\Requests\UploadDropzoneMediaRequest;
use Modules\Media\Http\Requests\OrderFileIntoGalleryRequest;
use Modules\Media\Http\Requests\UploadDropzoneGalleryFileRequest;

class MediaController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var FileRepository
     */
    private $file;

    /**
     * @var Imagy
     */
    private $imagy;

    /**
     * @var GalleryRepository
     */
    private $galleryRepository;

    /**
     * @var GalleryMediaFilesRepository
     */
    private $galleryMediaFilesRepository;

    /**
     * @var FolderRepository
     */
    private $folderRepository;

    /**
     * MediaController constructor.
     * @param FileService $fileService
     * @param FileRepository $file
     * @param Imagy $imagy
     * @param GalleryRepository $galleryRepository
     * @param GalleryMediaFilesRepository $galleryMediaFilesRepository
     * @param FolderRepository $folderRepository
     */
    public function __construct(
        FileService $fileService,
        FileRepository $file,
        Imagy $imagy,
        GalleryRepository $galleryRepository,
        GalleryMediaFilesRepository $galleryMediaFilesRepository,
        FolderRepository $folderRepository
    ) {
        $this->fileService = $fileService;
        $this->file = $file;
        $this->imagy = $imagy;
        $this->galleryRepository = $galleryRepository;
        $this->galleryMediaFilesRepository = $galleryMediaFilesRepository;
        $this->folderRepository = $folderRepository;
    }

    public function all()
    {
        $files = $this->file->allWithBuilder();

        return Datatables::eloquent($files)
            ->addColumn('thumbnail', function ($file) {
                if ($file->isFolder()) {
                    return '<i class="fa fa-folder" style="font-size: 20px;"></i>';
                }
                if ($file->isImage()) {
                    return '<img src="' . Imagy::getThumbnail($file->path, 'smallThumb') . '"/>';
                }

                return '<i class="fa ' . FileHelper::getFaIcon($file->media_type) . '" style="font-size: 20px;"></i>';
            })
            ->rawColumns(['thumbnail'])
            ->toJson();
    }

    public function allVue(Request $request)
    {
        return MediaTransformer::collection($this->file->serverPaginationFilteringFor($request));
    }

    public function find(File $file)
    {
        return new MediaTransformer($file);
    }

    public function findFirstByZoneEntity(Request $request)
    {
        $imageable = DB::table('media__imageables')
            ->where('imageable_id', $request->get('entity_id'))
            ->whereZone($request->get('zone'))
            ->whereImageableType($request->get('entity'))
            ->first();

        if ($imageable === null) {
            return response()->json(null);
        }

        $file = $this->file->find($imageable->file_id);

        if ($file === null) {
            return response()->json(['data' => null]);
        }

        return new MediaTransformer($file);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UploadMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'), $request->get('parent_id'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    public function storeDropzone(UploadDropzoneMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    public function update(File $file, Request $request)
    {
        $data = $request->except(['filename', 'path', 'extension', 'size', 'id', 'thumbnails','published_at']);
        $requestData = $request->all();
        $published_at = $requestData['published_at'];
        $data['published_at'] = $published_at;
        $this->file->update($file, $data);

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.file updated'),
        ]);
    }

    /**
     * Link the given entity with a media file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkMedia(Request $request) : JsonResponse
    {
        $mediaId = $request->get('mediaId');
        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');
        $order = $request->get('order');

        $entity = $entityClass::find($entityId);
        $zone = $request->get('zone');
        $entity->files()->attach($mediaId, [
            'imageable_type' => $entityClass,
            'zone' => $zone,
            'order' => $order,
        ]);
        $imageable = DB::table('media__imageables')->whereFileId($mediaId)
            ->whereZone($zone)
            ->whereImageableType($entityClass)
            ->first();
        $file = $this->file->find($imageable->file_id);

        $mediaType = FileHelper::getTypeByMimetype($file->mimetype);

        $thumbnailPath = $this->getThumbnailPathFor($mediaType, $file);

        event(new FileWasLinked($file, $entity));

        return response()->json([
            'error' => false,
            'message' => 'The link has been added.',
            'result' => [
                'path' => $thumbnailPath,
                'imageableId' => $imageable->id,
                'mediaType' => $mediaType,
                'mimetype' => $file->mimetype,
            ],
        ]);
    }

    /**
     * Remove the record in the media__imageables table for the given id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkMedia(Request $request) : JsonResponse
    {
        $imageableId = $request->get('imageableId');
        $deleted = DB::table('media__imageables')->whereId($imageableId)->delete();
        if (! $deleted) {
            return response()->json([
                'error' => true,
                'message' => 'The file was not found.',
            ]);
        }

        event(new FileWasUnlinked($imageableId));

        return response()->json([
            'error' => false,
            'message' => 'The link has been removed.',
        ]);
    }

    /**
     * Sort the record in the media__imageables table for the given array
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortMedia(Request $request) : JsonResponse
    {
        $imageableIdArray = $request->get('sortable');

        $order = 1;

        foreach ($imageableIdArray as $id) {
            DB::table('media__imageables')->whereId($id)->update(['order' => $order]);
            $order++;
        }

        return response()->json(['error' => false, 'message' => 'The items have been reorder.']);
    }

    public function destroy(File $file)
    {
        if( $file->galleries->count() !==0 ) {
            return response()->json([
                'error' => true,
                'message' => trans('media::messages.file is attach to gallery')
            ], 422);
        }

        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.file deleted'),
        ]);
    }

    /**
     * Get the path for the given file and type
     * @param string $mediaType
     * @param File $file
     * @return string
     */
    private function getThumbnailPathFor($mediaType, File $file) : string
    {
        if ($mediaType === 'image') {
            return $this->imagy->getThumbnail($file->path, 'mediumThumb');
        }

        return $file->path->getRelativeUrl();
    }

    /**
     * Method to upload file and attach to gallery
     *
     * @param UploadDropzoneGalleryFileRequest $request
     * @return JsonResponse
     */
    public function storeGalleryFileDropzone(
        UploadDropzoneGalleryFileRequest $request
    ) : JsonResponse {
        $gallery = $this->galleryRepository->find($request->galleryId);
        $savedFile = $this->fileService->storeGalleryFile($request->file('file'), $gallery);
        /** @var Gallery $gallery */
        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }
        event(new FileWasUploaded($savedFile));
        $mediaFiles = $this->galleryMediaFilesRepository->getLastItemInOrderFilesList($request->galleryId);
        $order = 1;
        if( $mediaFiles != null) {
            $order = (int)$mediaFiles->order + 1;
        }
        $this->galleryRepository->assignImagesToGallery($gallery, $savedFile, $order);

        return response()->json($savedFile->toArray());
    }

    /**
     * @param OrderFileIntoGalleryRequest $request
     * @return JsonResponse
     */
    public function updateOrderFileIntoGallery(OrderFileIntoGalleryRequest $request): JsonResponse
    {
        /** @var MediaFiles $mediaFile */
        $mediaFile = $this->galleryMediaFilesRepository->getItemByFileIdAndGalleryId(
            $request->fileId,
            $request->galleryId
        );

        $currentOrder = $mediaFile->order;
        if( $currentOrder > $request->fileOrder ) {
            $this->galleryMediaFilesRepository->increaseFileOrderNumber(
                $request->fileOrder,
                $currentOrder-1,
                $request->galleryId
            );
        } elseif ($currentOrder < $request->fileOrder) {
            $this->galleryMediaFilesRepository->reduceFileOrderNumber(
                $currentOrder+1,
                $request->fileOrder,
                $request->galleryId
            );
        }
        DB::table('gallery__media_files')
            ->where('gallery_id', $request->galleryId)
            ->where('file_id', $request->fileId)
            ->update(['order' => $request->fileOrder]);

        return response()->json([]);
    }
}
