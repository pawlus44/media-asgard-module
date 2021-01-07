<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("{$lang}.title") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[title]", trans('media::galleries.form.title')) !!}
            {!! Form::text("{$lang}[title]", $gallery->title, ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('media::galleries.form.title')]) !!}
            {!! $errors->first("{$lang}.title", '<span class="help-block">:message</span>') !!}
        </div>

        <div class='form-group{{ $errors->has("{$lang}.slug") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[slug]", trans('media::galleries.form.slug')) !!}
            {!! Form::text("{$lang}[slug]", $gallery->slug, ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('media::galleries.form.slug')]) !!}
            {!! $errors->first("{$lang}.slug", '<span class="help-block">:message</span>') !!}
        </div>

        <div class='form-group {{ $errors->has("{$lang}.status") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[body]", trans('media::galleries.form.status')) !!}
            {!! Form::select('status', $galleryStatus, $gallery->status, ['class' => 'form-control']) !!}
            {!! $errors->first("{$lang}.status", '<span class="help-block">:message</span>') !!}
        </div>
    </div>

</div>
