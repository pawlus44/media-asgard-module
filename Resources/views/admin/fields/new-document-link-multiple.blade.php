<div class="form-group">
    {!! Form::label($zone, $name) !!}
    <div class="clearfix"></div>

    <a class="btn btn-primary btn-browse" onclick="openDocumentWindowMultiple(event, '{{ $zone }}', '{{ $url }}');" <?php echo (isset($media->path))?'style="display:none;"':'' ?>>
        <i class="fa fa-upload"></i>
        {{ trans('media::media.Browse') }}
    </a>

    <div class="clearfix"></div>

    <div class="jsThumbnailImageWrapper">
        <?php if (isset($media) && !$media->isEmpty()): ?>
            <?php $order_list = [] ?>
            <?php foreach ($media as $file): ?>
                <?php $order_list[$zone][] = $file->id; ?>
                <figure data-id="{{ $file->id }}">
                    <i class="fa fa-file" style="font-size: 20px;"></i>
                    <a class="jsRemoveLink" href="#" data-id="{{ $file->pivot->id }}">
                        <i class="fa fa-times-circle removeIcon"></i><span class="insert-filename" style="padding-left: 5px; padding-right: 15px;">{{ $file->filename }}</span>
                    </a>
                    <input type="hidden" name="medias_multi[{{ $zone }}][files][]" value="{{ $file->id }}">
                </figure>
            <?php endforeach; ?>
                <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="{{ implode(',', $order_list[$zone]) }}" class="orders">
            <?php else: ?>
                <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="" class="orders">
        <?php endif; ?>
    </div>
</div>