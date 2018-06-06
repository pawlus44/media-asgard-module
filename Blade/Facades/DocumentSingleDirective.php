<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class DocumentSingleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'document.single.directive';
    }
}
