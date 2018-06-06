<div class="form-group">
    {!! Form::label($zone, $name) !!}
    <div class="clearfix"></div>

    <a class="btn btn-primary btn-browse" onclick="openDocumentWindowSingle(event, '{{ $zone }}', '{{ $url }}');" <?php echo (isset($media->path))?'style="display:none;"':'' ?>>
        <i class="fa fa-upload"></i>
        {{ trans('media::media.Browse') }}
    </a>

    <div class="clearfix"></div>

    <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
        <?php if (isset($media->path)): ?>
        <figure data-id="{{ $media->id }}">
            <i class="fa fa-file" style="font-size: 20px;"></i>
            <a class="jsRemoveSimpleLink" href="#" data-id="{{ $media->pivot->id }}">
                <i class="fa fa-times-circle removeIcon"></i><span class="insert-filename" style="padding-left: 5px; padding-right: 15px;">{{ $media->filename }}</span>
            </a>
        </figure>
        <input type="hidden" name="medias_single[{{ $zone }}]" value="{{ $media->id }}">
        <?php else: ?>
        <input type="hidden" name="medias_single[{{ $zone }}]" value="">
        <?php endif; ?>
    </div>
</div>
