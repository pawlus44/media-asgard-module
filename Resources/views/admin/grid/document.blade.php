@include('media::admin.grid.partials.content', ['isWysiwyg' => false])
<script>
    $(document).ready(function () {
        $('.jsInsertImage').on('click', function (e) {
            e.preventDefault();
            var mediaId = $(this).data('id'),
                filePath = $(this).data('file-path'),
                mediaType = $(this).data('mediaType'),
                mimetype = $(this).data('mimetype'),
                filename = $(this).data('filename');
            if(window.opener.old) {
                if(window.opener.single) {
                    window.opener.includeMediaSingleOld(mediaId, filePath);
                    window.close();
                } else {
                    window.opener.includeMediaMultipleOld(mediaId, filePath);
                }
            } else {
                if(window.opener.single) {
                    window.opener.includeDocumentSingle(mediaId, filePath, mediaType, mimetype, filename);
                    window.close();
                } else {
                    window.opener.includeDocumentMultiple(mediaId, filePath, mediaType, mimetype, filename);
                }
            }
        });
    });
</script>
</body>
</html>
