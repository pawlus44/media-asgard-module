<?php

return [
    'media.medias' => [
        'index' => 'media::media.list resource',
        'create' => 'media::media.create resource',
        'edit' => 'media::media.edit resource',
        'destroy' => 'media::media.destroy resource',
    ],
    'media.folders' => [
        'index' => 'media::folders.list resource',
        'create' => 'media::folders.create resource',
        'edit' => 'media::folders.edit resource',
        'destroy' => 'media::folders.destroy resource',
    ],
    'media.gallery' => [
        'index' => 'media::gallery.index resource',
        'create' => 'media::gallery.create resource',
        'store' => 'media::gallery.store resource',
        'edit' => 'media::gallery.edit resource',
        'update' => 'media::gallery.update resource',
        'destroy' => 'media::gallery.destroy resource',
        'images' => 'media::gallery.images resource',
        'storeImages' => 'media::gallery.storeImages resource',
        'imageDestroy' => 'media::gallery.imageDestroy resource',
        'imageEdit' => 'media::gallery.imageEdit resource',
        'imageUpdate' => 'media::gallery.imageUpdate resource'
    ],
    'media.galleries' => [
        'index' => 'media::galleries.index resource',
        'create' => 'media::galleries.create resource',
        'store' => 'media::galleries.store resource',
        'edit' => 'media::galleries.edit resource',
        'update' => 'media::galleries.update resource',
        'destroy' => 'media::galleries.destroy resource',
        'images' => 'media::galleries.images resource',
        'storeImages' => 'media::galleries.storeImages resource',
    ]
];
