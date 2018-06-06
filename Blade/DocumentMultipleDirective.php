<?php

namespace Modules\Media\Blade;

use Modules\Media\Composers\Backend\PartialAssetComposer;

class DocumentMultipleDirective
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
     * @var string|null
     */
    private $url;

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $view = $this->view ?: 'media::admin.fields.new-document-link-multiple';
        view()->composer($view, PartialAssetComposer::class);

        $zone = $this->zone;

        $name = $this->name ?: ucwords(str_replace('_', ' ', $this->zone));

        $url = $this->url;


        if ($this->entity !== null) {
            $media = $this->entity->filesByZone($this->zone)->get();
        }
        
        return view($view, compact('media', 'zone', 'name', 'url'));
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->zone = array_get($arguments, 0);
        $this->url = array_get($arguments,1);
        $this->entity = array_get($arguments, 2);
        $this->view = array_get($arguments, 3);
        $this->name = array_get($arguments, 4);
    }
}
