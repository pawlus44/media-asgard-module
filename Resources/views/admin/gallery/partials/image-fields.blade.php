<div class="row">
    <div class="col-md-12">
        <form method="POST" class="dropzone">
            {!! Form::token() !!}
            {!! Form::hidden('idGallery', $gallery->id); !!}
        </form>
    </div>
</div>