<div class="form-group">
    <div class="clearfix"></div>
    <a class="btn btn-primary btn-upload" onclick="openMediaWindowMultipleWithoutUpload(event, '{{ $zone }}')"><i class="fa fa-upload"></i>
        {{ trans('media::media.Browse') }}
    </a>
    <div class="clearfix"></div>
    <div class="jsThumbnailImageWrapper">
        {!! Form::open(['route' => ['admin.media.gallery.image.attach'], 'method' => 'post', "id" =>"form-attach-files-to-gallery" ]) !!}
            <div class="list-attach-files">
                <?php if (isset($media) && !$media->isEmpty()): ?>
                    <?php $order_list = [] ?>
                    <?php foreach ($media as $file): ?>
                        <?php $order_list[$zone][] = $file->id; ?>
                        <figure data-id="{{ $file->id }}">
                            <?php if ($file->media_type === 'image'): ?>
                            <img src="{{ Imagy::getThumbnail($file->path, (isset($thumbnailSize) ? $thumbnailSize : 'mediumThumb')) }}" alt="{{ $file->alt_attribute }}"/>
                            <?php elseif ($file->media_type === 'video'): ?>
                            <video src="{{ $file->path }}"  controls width="320"></video>
                            <?php elseif ($file->media_type === 'audio'): ?>
                            <audio controls><source src="{{ $file->path }}" type="{{ $file->mimetype }}"></audio>
                            <?php else: ?>
                            <i class="fa fa-file" style="font-size: 50px;"></i>
                            <?php endif; ?>
                            <a class="jsRemoveLink" href="#" data-id="{{ $file->pivot->id }}">
                                <i class="fa fa-times-circle removeIcon"></i>
                            </a>
                            <input type="hidden" name="medias_multi[{{ $zone }}][files][]" value="{{ $file->id }}">
                        </figure>
                        <?php endforeach; ?>
                        <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="{{ implode(',', $order_list[$zone]) }}" class="orders">
                <?php else: ?>
                    <input type="hidden" name="gallery_id" value="{{ $galleryId }}">
                    <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="" class="orders">
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
            <div>
                <button id="btn-attach-file-to-gallery" class="btn btn-warning" type="submit">Dodaj do galerii</button>
            </div>
        {!! Form::close() !!}
    </div>
</div>