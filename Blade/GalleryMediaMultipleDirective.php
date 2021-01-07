<?php

namespace Modules\Media\Blade;

use Modules\Media\Composers\Backend\PartialAssetComposer;

class GalleryMediaMultipleDirective
{
    /**
     * @var string
     */
    private $zone;
    /**
     * @var
     */
    private $entity;
    /**
     * @var string|null
     */
    private $view;
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var int
     */
    private $galleryId;

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $view = $this->view ?: 'media::admin.fields.new-file-link-multiple';
        view()->composer($view, PartialAssetComposer::class);

        $zone = $this->zone;

        $name = $this->name ?: ucwords(str_replace('_', ' ', $this->zone));

        $media = null;
        if ($this->entity !== null) {
            $media = $this->entity->filesByZone($this->zone)->get();
        }

        $galleryId = $this->galleryId;

        return view($view, compact('media', 'zone', 'name', 'galleryId'));
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->zone = array_get($arguments, 0);
        $this->entity = array_get($arguments, 1);
        $this->view = array_get($arguments, 2);
        $this->name = array_get($arguments, 3);
        $this->galleryId = array_get($arguments, 4);
    }
}
