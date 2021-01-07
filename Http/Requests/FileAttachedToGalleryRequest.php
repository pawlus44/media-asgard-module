<?php

declare(strict_types=1);

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FileAttachedToGalleryRequest
 * @package Modules\Media\Http\Requests
 */
class FileAttachedToGalleryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'gallery_id' => ['required', 'integer'],
            'medias_multi' => ['required', 'array']
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}