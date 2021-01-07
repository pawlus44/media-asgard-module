<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class GalleryMediaMultipleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gallery.media.multiple.directive';
    }
}
