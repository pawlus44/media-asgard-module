<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->bind('media', function ($id) {
    return app(\Modules\Media\Repositories\FileRepository::class)->find($id);
});

$router->bind('gallery', function ($id) {
    return app(\Modules\Media\Repositories\GalleryRepository::class)->find($id);
});

$router->group(['prefix' => '/media'], function (Router $router) {
    $router->get('media', [
        'as' => 'admin.media.media.index',
        'uses' => 'MediaController@index',
        'middleware' => 'can:media.medias.index',
    ]);
    $router->get('media/create', [
        'as' => 'admin.media.media.create',
        'uses' => 'MediaController@create',
        'middleware' => 'can:media.medias.create',
    ]);
    $router->post('media', [
        'as' => 'admin.media.media.store',
        'uses' => 'MediaController@store',
        'middleware' => 'can:media.medias.create',
    ]);
    $router->get('media/{media}/edit', [
        'as' => 'admin.media.media.edit',
        'uses' => 'MediaController@edit',
        'middleware' => 'can:media.medias.edit',
    ]);
    $router->put('media/{media}', [
        'as' => 'admin.media.media.update',
        'uses' => 'MediaController@update',
        'middleware' => 'can:media.medias.edit',
    ]);
    $router->delete('media/{media}', [
        'as' => 'admin.media.media.destroy',
        'uses' => 'MediaController@destroy',
        'middleware' => 'can:media.medias.destroy',
    ]);

    $router->get('media-grid/index', [
        'uses' => 'MediaGridController@index',
        'as' => 'media.grid.select',
        'middleware' => 'can:media.medias.index',
    ]);

    $router->get('media-grid/without-upload', [
        'uses' => 'MediaGridController@withoutUpload',
        'as' => 'media.grid.select.without.upload',
        'middleware' => 'can:media.medias.index',
    ]);

    $router->get('media-grid/type/{type}', [
        'uses' => 'MediaGridController@type',
        'as' => 'media.grid.type',
        'middleware' => 'can:media.medias.index',
    ]);

    $router->get('media-grid/ckIndex', [
        'uses' => 'MediaGridController@ckIndex',
        'as' => 'media.grid.ckeditor',
        'middleware' => 'can:media.medias.index',
    ]);
});

$router->group(['prefix' =>'/media/gallery'], function (Router $router) {
    $router->get('galleries', [
        'as' => 'admin.media.gallery.index',
        'uses' => 'GalleryController@index',
        'middleware' => 'can:media.galleries.index'
    ]);
    $router->get('galleries/create', [
        'as' => 'admin.media.gallery.create',
        'uses' => 'GalleryController@create',
        'middleware' => 'can:media.galleries.create'
    ]);
    $router->post('galleries', [
        'as' => 'admin.media.gallery.store',
        'uses' => 'GalleryController@store',
        'middleware' => 'can:media.galleries.store'
    ]);
    $router->get('galleries/{gallery}/edit', [
        'as' => 'admin.media.gallery.edit',
        'uses' => 'GalleryController@edit',
        'middleware' => 'can:media.galleries.edit'
    ]);
    $router->put('galleries/{gallery}', [
        'as' => 'admin.media.gallery.update',
        'uses' => 'GalleryController@update',
        'middleware' => 'can:media.galleries.update'
    ]);
    $router->delete('galleries/{gallery}', [
        'as' => 'admin.media.gallery.destroy',
        'uses' => 'GalleryController@destroy',
        'middleware' => 'can:media.galleries.destroy'
    ]);
// append
    $router->get('galleries/{gallery}/images', [
        'as' => 'admin.media.gallery.images',
        'uses' => 'GalleryController@images',
        'middleware' => 'can:media.galleries.images'
    ]);

    //image into gallery
    $router->delete('galleries/{gallery}/image/{media}', [
        'as' => 'admin.media.gallery.image.destroy', 
        'uses' => 'GalleryController@imageDestroy',
        'middleware' => 'can:media.galleries.images'
    ]);

    $router->get('galleries/{gallery}/image/{media}/edit', [
        'as' => 'admin.media.gallery.image.edit', 
        'uses' => 'GalleryController@imageEdit',
        'middleware' => 'can:media.galleries.images'
    ]);

    $router->put('galleries/{gallery}/image/{media}/update', [
        'as' => 'admin.media.gallery.image.update', 
        'uses' => 'GalleryController@imageUpdate',
        'middleware' => 'can:media.galleries.images'
    ]);

    $router->post('galleries/files/attach', [
        'as' => 'admin.media.gallery.image.attach',
        'uses' => 'GalleryController@attachFilesToGallery'
    ]);

    $router->delete('galleries/{gallery}/image/{media}/order/{order}', [
        'as' => 'admin.media.gallery.image.detach',
        'uses' => 'GalleryController@imageDetach',
        'middleware' => 'can:media.galleries.images'
    ]);
});