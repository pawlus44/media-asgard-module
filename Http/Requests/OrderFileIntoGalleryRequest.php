<?php

//declare(strict_types=1);

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFileIntoGalleryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'galleryId' => ['required', 'integer'],
            'fileId' => ['required', 'integer'],
            'fileOrder' => ['required', 'integer']
        ];
    }

    public function authorize()
    {
        return true;
    }
}