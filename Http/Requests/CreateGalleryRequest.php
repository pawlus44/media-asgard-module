<?php namespace Modules\Media\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateGalleryRequest extends BaseFormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return[];

    }

    public function translationRules()
    {
        return [
            'title' => 'required|max:255|unique:gallery__gallery_translations,title',
            'slug'  => 'required|max:255|unique:gallery__gallery_translations,slug'
        ];
    }


    public function translationMessages()
    {
        return[
            'title.required' => trans('offers::abcs.form.required'),
            'slug.required' => trans('offers::abcs.form.required'),
            'title.unique' => trans('offers::abcs.form.unique'),
            'slug.unique' => trans('offers::abcs.form.unique')
        ];
    }

}
