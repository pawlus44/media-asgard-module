@include('media::admin.grid.partials.content-without-upload', ['isWysiwyg' => false])
<script>
    $(document).ready(function () {
        $('.jsInsertImage').on('click', function (e) {
            e.preventDefault();
            var mediaId = $(this).data('id'),
                filePath = $(this).data('file-path'),
                mediaType = $(this).data('mediaType'),
                mimetype = $(this).data('mimetype');
                window.opener.includeMediaMultipleToGalleryWithSaveButton(mediaId, filePath, mediaType, mimetype);
        });
    });
</script>
</body>
</html>
