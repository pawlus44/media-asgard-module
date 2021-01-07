<?php namespace Modules\Media\Http\Controllers\Admin;

use Modules\Media\Repositories\FolderRepository;
use Validator;
use Illuminate\Http\Request;
use Modules\Media\Entities\MediaFiles;
use Modules\Media\Image\Imagy;
use Modules\Media\Entities\File;
use Modules\Media\Entities\Gallery;
use Modules\Media\Image\ThumbnailsManager;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\GalleryRepository;
use Modules\Media\Http\Requests\UpdateMediaRequest;
use Modules\Media\Http\Requests\CreateGalleryRequest;
use Modules\Media\Repositories\GalleryMediaFilesRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Http\Requests\FileAttachedToGalleryRequest;

class GalleryController extends AdminBaseController
{
    /**
     * @var array
     */
    private $galleryStatus;

    /**
     * @var array
     */
    private $typeOfGallery;

    /**
     * @var Imagy
     */
    private $imagy;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var ThumbnailsManager
     */
    private $thumbnailsManager;

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
     * GalleryController constructor.
     * @param GalleryRepository $gallery
     * @param ThumbnailsManager $thumbnailsManager
     * @param Imagy $imagy
     * @param FileRepository $fileRepository
     * @param GalleryRepository $galleryRepository
     * @param GalleryMediaFilesRepository $galleryMediaFilesRepository
     */
    public function __construct(
        GalleryRepository $gallery,
        ThumbnailsManager $thumbnailsManager,
        Imagy $imagy,
        FileRepository $fileRepository,
        GalleryRepository $galleryRepository,
        GalleryMediaFilesRepository $galleryMediaFilesRepository,
        FolderRepository $folderRepository
    )
    {
        parent::__construct();

        $this->galleryStatus = [
            1 => trans('media::galleries.status.active'),
            2 => trans('media::galleries.status.turn off')
        ];

        $this->typeOfGallery = [
            1 => trans('media::galleries.type.ordinary'),
            2 => trans('media::galleries.type.article'),
            3 => trans('media::galleries.type.offers')
        ];

        $this->thumbnailsManager = $thumbnailsManager;
        $this->imagy = $imagy;
        $this->fileRepository = $fileRepository;
        $this->galleryRepository = $galleryRepository;
        $this->galleryMediaFilesRepository = $galleryMediaFilesRepository;
        $this->folderRepository = $folderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $galleries = $this->galleryRepository->all();
        return view('media::admin.gallery.index',
            [
                'galleries' => $galleries,
                'galleryStatus' => $this->galleryStatus
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('media::admin.gallery.create', ['galleryStatus' => $this->galleryStatus, 'typeOfGallery' => $this->typeOfGallery]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateGalleryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateGalleryRequest $request)
    {
        $mainGalleryFolder = $this->fileRepository->getFolderByName('gallery');
        if( is_null($mainGalleryFolder) ) {
            $mainGalleryFolder = $this->folderRepository->create(
                [
                    'name' => 'gallery',
                    'parent_id' => 0
                ]
            );
        }

        /** @var Gallery $gallery */
        $gallery = $this->galleryRepository->create($request->all());
        $galleryFolder = $this->folderRepository->createGalleryFolder($gallery, $mainGalleryFolder);
        dd($galleryFolder->id);
        $gallery->folder_id = $galleryFolder->id;
        $gallery->save();

        return redirect()->route('admin.media.gallery.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Gallery $gallery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Gallery $gallery)
    {
        return view('media::admin.gallery.edit', ['gallery' => $gallery, 'galleryStatus' => $this->galleryStatus, 'typeOfGallery' => $this->typeOfGallery]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Gallery $gallery
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Gallery $gallery, Request $request)
    {
        $this->galleryRepository->update($gallery, $request->all());

        return redirect()->route('admin.media.gallery.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Gallery $gallery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Gallery $gallery)
    {
        $this->galleryRepository->destroy($gallery);

        return redirect()->route('admin.media.gallery.index');
    }

    /**
     * Method to get file attached to gallery
     *
     * @param Gallery $gallery
     * @return mixed
     */
    public function images(Gallery $gallery)
    {
        return view('media::admin.gallery.images', ['gallery' => $gallery]);
    }

    public function imagesAndBack(Gallery $gallery, $type, $id)
    {
        return view('media::admin.gallery.images', ['gallery' => $gallery], $type, $id);
    }

    public function storeImages(Request $request)
    {
        return redirect()->route('media::admin.gallery.images', ['gallery' => 1]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param File $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function imageEdit(Gallery $gallery, File $file)
    {
        $thumbnails = $this->thumbnailsManager->all();

        return view(
            'media::admin.gallery.image.edit',
            [
                'file' => $file,
                'thumbnails' => $thumbnails,
                'gallery' => $gallery
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param File $file
     * @param UpdateMediaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageUpdate(Gallery $gallery, File $file, UpdateMediaRequest $request)
    {
        $this->fileRepository->update($file, $request->all());

        return redirect()->route('admin.media.gallery.images', ['gallery' => $gallery->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse
     * @internal param int $id
     *
     */
    public function imageDestroy(Gallery $gallery, File $file)
    {
        $this->imagy->deleteAllFor($file);
        $this->fileRepository->destroy($file);

        return redirect()->route('admin.media.gallery.images', ['gallery' => $gallery->id]);
    }

    /**
     * Attach file to gallery
     *
     * @param FileAttachedToGalleryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachFilesToGallery(FileAttachedToGalleryRequest $request)
    {
        $lastFileAttachedToGallery = $this->galleryMediaFilesRepository
            ->getLastItemInOrderFilesList($request->gallery_id);
        $lastOrder = $lastFileAttachedToGallery->order;

        $rules = [
            'medias_multi.image.files.*' => 'integer', // check each item in the array
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route(
                'admin.media.gallery.images',
                [
                    'gallery' => $request->gallery_id,
                ]
            )
            ->withErrors($validator)
            ->withInput();
        }

        $order = $lastOrder+1;
        foreach ($request->medias_multi["image"]["files"] as $fileId) {
            $mediaFiles = new MediaFiles([
                'gallery_id' => $request->gallery_id,
                'file_id' => (int)$fileId,
                'order' => $order
            ]);
            $mediaFiles->save();
            $order = $order + 1;
        }

        return redirect()->route('admin.media.gallery.images', ['gallery' => $request->gallery_id]);
    }

    /**
     * @param Gallery $gallery
     * @param File $file
     * @param int $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageDetach(Gallery $gallery, File $file, int $order)
    {
        $lastItemFromGallery = $this->galleryMediaFilesRepository->getLastItemInOrderFilesList($gallery->id);
        $this->galleryMediaFilesRepository->detachFileFromGallery($gallery->id, $file->id, $order);
        $this->galleryMediaFilesRepository->reduceFileOrderNumber(
            $order+1,
            $lastItemFromGallery->order,
            $gallery->id
        );
        return redirect()->route('admin.media.gallery.images', ['gallery' => $gallery->id]);
    }
}
