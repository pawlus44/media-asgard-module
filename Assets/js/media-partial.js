$( document ).ready(function() {
	var $fileCount = $('.jsFileCount');
	var sortableWrapper = $(".jsThumbnailImageWrapper");
	var sortableSelection = sortableWrapper.not(".jsSingleThumbnailWrapper");

	// This comes from new-file-link-single
	if (typeof window.openMediaWindowSingle === 'undefined') {
		window.mediaZone = '';
		window.openMediaWindowSingle = function (event, zone) {
			window.single = true;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(Asgard.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}

	// This comes from new-document-link-single
	if (typeof window.openDocumentWindowSingle === 'undefined') {
		window.mediaZone = '';
		window.openDocumentWindowSingle = function (event, zone, url) {
			window.single = true;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(url, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}

	if (typeof window.includeDocumentSingle === 'undefined') {
		window.includeDocumentSingle = function (mediaId, filePath, mediaType, mimetype, filename) {
			var mediaPlaceholder;

			mediaPlaceholder = '<i class="fa fa-file" style="font-size: 20px;"></i>'+'<span style="padding-left: 5px; margin-right: 15px;">'+filename+'</span>';
			
			var html = '<figure data-id="'+ mediaId +'">' + mediaPlaceholder +
				'<a class="jsRemoveSimpleLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i></a>' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn('slow', function() {
				toggleButton($(this));
			});
            
            window.zoneWrapper.children('input').val(mediaId);
		};
	}

/// MULTIPLE DOCUMENT

	// This comes from new-file-link-multiple
	if (typeof window.openMediaWindowMultiple === 'undefined') {
		window.mediaZone = '';
		window.openDocumentWindowMultiple = function (event, zone, url) {
			window.single = false;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(url, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}
	if (typeof window.includeDocumentMultiple === 'undefined') {
		window.includeDocumentMultiple = function (mediaId, filePath, mediaType, mimetype, filename) {
			var mediaPlaceholder;

			mediaPlaceholder = '<i class="fa fa-file" style="font-size: 20px;"></i>'+'<span style="padding-left: 5px; margin-right: 15px;">'+filename+'</span>';;

			var html = '<figure data-id="' + mediaId + '">' + mediaPlaceholder +
				'<a class="jsRemoveLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i>' +
				'</a>' +
				'<input type="hidden" name="medias_multi[' + window.mediaZone + '][files][]" value="' + mediaId + '">' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn();
			window.zoneWrapper.trigger('sortupdate');
			if ($fileCount.length > 0) {
				var count = parseInt($fileCount.text());
				$fileCount.text(count + 1);
			}
		};
	}


/// END MULTIPLE DOCUMENT

	if (typeof window.includeMediaSingle === 'undefined') {
		window.includeMediaSingle = function (mediaId, filePath, mediaType, mimetype) {
			var mediaPlaceholder;

			if (mediaType === 'image') {
				mediaPlaceholder = '<img src="' + filePath + '" alt=""/>';
			} else if (mediaType == 'video') {
				mediaPlaceholder = '<video src="' + filePath + '" controls width="320"></video>';
			} else if (mediaType == 'audio') {
				mediaPlaceholder = '<audio controls><source src="' + filePath + '" type="' + mimetype + '"></audio>'
			} else {
				mediaPlaceholder = '<i class="fa fa-file" style="font-size: 50px;"></i>';
			}

			var html = '<figure data-id="'+ mediaId +'">' + mediaPlaceholder +
				'<a class="jsRemoveSimpleLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i></a>' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn('slow', function() {
				toggleButton($(this));
			});
                        window.zoneWrapper.children('input').val(mediaId);
		};
	}

	// This comes from new-file-link-multiple
	if (typeof window.openMediaWindowMultiple === 'undefined') {
		window.mediaZone = '';
		window.openMediaWindowMultiple = function (event, zone) {
			window.single = false;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(Asgard.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}
	if (typeof window.openMediaWindowMultipleWithoutUpload === 'undefined') {
		window.mediaZone = '';
		window.openMediaWindowMultipleWithoutUpload = function (event, zone) {
			window.single = false;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $('#form-attach-files-to-gallery .list-attach-files');
			window.open(Asgard.mediaGridSelectUrlWithoutUpload, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}
	if (typeof window.includeMediaMultiple === 'undefined') {
		window.includeMediaMultiple = function (mediaId, filePath, mediaType, mimetype) {
			var mediaPlaceholder;

			if (mediaType === 'image') {
				mediaPlaceholder = '<img src="' + filePath + '" alt=""/>';
			} else if (mediaType == 'video') {
				mediaPlaceholder = '<video src="' + filePath + '" controls width="320"></video>';
			} else if (mediaType == 'audio') {
				mediaPlaceholder = '<audio controls><source src="' + filePath + '" type="' + mimetype + '"></audio>'
			} else {
				mediaPlaceholder = '<i class="fa fa-file" style="font-size: 50px;"></i>';
			}

			var html = '<figure data-id="' + mediaId + '">' + mediaPlaceholder +
				'<a class="jsRemoveLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i>' +
				'</a>' +
				'<input type="hidden" name="medias_multi[' + window.mediaZone + '][files][]" value="' + mediaId + '">' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn();
			window.zoneWrapper.trigger('sortupdate');
			if ($fileCount.length > 0) {
				var count = parseInt($fileCount.text());
				$fileCount.text(count + 1);
			}
		};
	}

	if (typeof window.includeMediaMultipleToGalleryWithSaveButton === 'undefined') {
		window.includeMediaMultipleToGalleryWithSaveButton = function (mediaId, filePath, mediaType, mimetype) {
			var mediaPlaceholder;

			if (mediaType === 'image') {
				mediaPlaceholder = '<img src="' + filePath + '" alt=""/>';
			}
			var html = '<figure data-id="' + mediaId + '">' + mediaPlaceholder +
				'<a class="jsRemoveLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i>' +
				'</a>' +
				'<input type="hidden" name="medias_multi[' + window.mediaZone + '][files][]" value="' + mediaId + '">' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn();
			window.zoneWrapper.trigger('sortupdate');
			if ($fileCount.length > 0) {
				var count = parseInt($fileCount.text());
				$fileCount.text(count + 1);
			}
			if($('figure', window.zoneWrapper).length == 1) {
				$('#btn-attach-file-to-gallery').show();
			}
		};
	}


	// This comes from new-file-link-multiple
	sortableWrapper.on('click', '.jsRemoveLink', function (e) {
		e.preventDefault();
		var pictureWrapper = $(this).parent();
		var pictureSortable = pictureWrapper.parent();

		pictureWrapper.fadeOut().remove();
		pictureSortable.trigger('sortupdate');

		if ($fileCount.length > 0) {
			var count = parseInt($fileCount.text());
			$fileCount.text(count - 1);
		}

		if($('figure', window.zoneWrapper).length == 0) {
			$('#btn-attach-file-to-gallery').hide();
		}
	});

	// This comes from new-file-link-multiple
	sortableSelection.sortable({
		items: "figure",
		placeholder: 'ui-state-highlight',
		cursor:'move',
		helper: 'clone',
		containment: 'parent',
		forcePlaceholderSize: false,
		forceHelperSize: true
	});

	sortableSelection.on('sortupdate', function(e, ui) {
		var dataSortable = $(this).sortable('toArray', {attribute: 'data-id'});
		$(this).find($('.orders')).val(dataSortable);
	});

	// This comes from new-file-link-single
	sortableWrapper.off('click', '.jsRemoveSimpleLink');
	sortableWrapper.on('click', '.jsRemoveSimpleLink', function (e) {
		e.preventDefault();
		$(e.delegateTarget).fadeOut('slow', function() {
			toggleButton($(this));
		}).children('figure').remove();
		$(e.delegateTarget).children('input').val('');
	});

	function toggleButton(el) {
		var browseButton = el.parent().find('.btn-browse');
		browseButton.toggle();
	}

	function moveUp($item) {
		$before = $item.prev();
		$item.insertBefore($before);
	}

	function moveDown($item) {
		$after = $item.next();
		$item.insertAfter($after);
	}

	function updateFilesOrder() {
		$('.jsGalleryFileList tbody tr').each(function( index ) {
			$('.order-column' ,this ).text(index+1);
		});
	}

	$('.move-up').click(function (){
		moveUp($(this).parents('tr'));
		updateFilesOrder();
		var order = parseInt($('.order-column',$(this).parents('tr')).text());
		var formData = new FormData();
		formData.append('galleryId',$('.jsGalleryFileList').data('galleryId'));
		formData.append('fileId',$(this).parents('tr').data('fileId'));
		formData.append('fileOrder', order);
		var object = {};
		formData.forEach(function (value, key) {
			object[key] = value;
		});
		var request = $.ajax({
			type: "PUT",
			url: "/api/gallery/file/order",
			data: object,
			beforeSend: function (xhr) {
				xhr.setRequestHeader ("Authorization", AuthorizationHeaderValue);
			}
		});
		request.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log('error');
		});
	});

	$('.move-down').click(function (){
		moveDown($(this).parents('tr'));
		updateFilesOrder();
		var order = parseInt($('.order-column',$(this).parents('tr')).text());
		var formData = new FormData();
		formData.append('galleryId',$('.jsGalleryFileList').data('galleryId'));
		formData.append('fileId',$(this).parents('tr').data('fileId'));
		formData.append('fileOrder', order);
		var object = {};
		formData.forEach(function (value, key) {
			object[key] = value;
		});
		var request = $.ajax({
			type: "PUT",
			url: "/api/gallery/file/order",
			data: object,
			beforeSend: function (xhr) {
				xhr.setRequestHeader ("Authorization", AuthorizationHeaderValue);
			}
		});
		request.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log('error');
		});
	});

/*	$('#btn-attach-file-to-gallery').click(function(){
		var object = {};
		object["galleryId"] = $('.jsGalleryFileList').data('galleryId');
		$('.list-attach-files figure').each(function (){
			object["galleryId"][] = this.data("id");
		});

		console.log(object);
	});*/
});
