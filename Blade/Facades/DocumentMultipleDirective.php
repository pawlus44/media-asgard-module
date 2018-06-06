<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class DocumentMultipleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'document.multiple.directive';
    }
}
