@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('media::galleries.title.images gallery') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.media.gallery.index') }}">{{ trans('media::galleries.title.galleries') }}</a></li>
        <li class="active">{{ trans('media::galleries.title.images gallery') }}</li>
    </ol>
@stop

@section('styles')
    <link href="{!! Module::asset('media:css/dropzone.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! Module::asset('media:css/gallery.css') !!}" rel="stylesheet" type="text/css" />
    <style>
    .dropzone {
        border: 1px dashed #CCC;
        min-height: 227px;
        margin-bottom: 20px;
    }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('media::admin.gallery.partials.image-fields')
            </div>
        </div>
    </div>
    @galleryMediaMultiple('image',null,'media::admin.fields.new-file-link-multiple-without-upload',"Dołącz zdjęcie z dysku", $gallery->id)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table class="data-table table table-bordered table-hover jsFileList jsGalleryFileList" data-gallery-id="{{$gallery->id}}">
                        <thead>
                            <tr>
                                <th>{{ trans('core::core.table.orderInLinst') }}</th>
                                <th>{{ trans('core::core.table.thumbnail') }}</th>
                                <th>{{ trans('media::media.table.filename') }}</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.actions.sortable') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($gallery->files): ?>
                                <?php foreach ($gallery->files as $file): ?>
                                    <tr data-file-id="{{$file->id}}">
                                        <td class="order-column">
                                            {{ $file->pivot->order }}
                                        </td>
                                        <td>
                                            <?php if ($file->isImage()): ?>
                                                <img src="{{ Imagy::getThumbnail($file->path, 'smallThumb') }}" alt=""/>
                                            <?php else: ?>
                                                <i class="fa fa-file" style="font-size: 20px;"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.media.media.edit', [$file->id]) }}">
                                                {{ $file->filename }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.media.media.edit', [$file->id]) }}">
                                                {{ $file->created_at }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-default btn-flat move-up"><i class="fa fa-caret-square-o-up"></i></button>
                                                <button class="btn btn-default btn-flat move-down"><i class="fa fa-caret-square-o-down"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.media.gallery.image.edit', [$gallery->id,$file->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                                <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.media.gallery.image.detach', [$gallery->id,$file->id,$file->pivot->order]) }}"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>{{ trans('core::core.table.orderInLinst') }}</th>
                                <th>{{ trans('core::core.table.thumbnail') }}</th>
                                <th>{{ trans('media::media.table.filename') }}</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.actions.sortable') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')

@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')

@stop

@section('scripts')
    <script src="{!! Module::asset('media:js/dropzone.js') !!}"></script>
    <?php $config = config('asgard.media.config'); ?>
    <script>
        var maxFilesize = '<?php echo $config['max-file-size'] ?>',
            acceptedFiles = '<?php echo $config['allowed-types'] ?>',
            galleryId = '<?php echo $gallery->id; ?>'
        ;
    </script>
    <script src="{!! Module::asset('media:js/init-dropzone-gallery-files.js') !!}"></script>
@stop
