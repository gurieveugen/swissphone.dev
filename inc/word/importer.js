function sp_cancelQueue(instance) {
	instance.stopUpload();
	var stats;
	do {
		stats = instance.getStats();
		instance.cancelUpload();
	} while (stats.files_queued !== 0);
}

function sp_uploadDebug( text ) {
	window.console && console.log( text );
}

function sp_fileDialogStart() {
	/* I don't need to do anything here */
}

function sp_fileQueued(file) {
	/* I don't need to do anything here */
}

function sp_fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			//progress.setStatus("File is too big.");
			sp_uploadDebug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			//progress.setStatus("Cannot upload Zero Byte files.");
			sp_uploadDebug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			//progress.setStatus("Invalid File Type.");
			sp_uploadDebug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			alert("You have selected too many files.  " +  (message > 1 ? "You may only add " +  message + " more files" : "You cannot add any more files."));
			break;
		default:
			if (file !== null) {
				//progress.setStatus("Unhandled Error");
			}
			sp_uploadDebug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        sp_uploadDebug(ex);
    }
}

function sp_fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (this.getStats().files_queued > 0) {
			//document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start and I can do that here */
		this.startUpload();
	} catch (ex)  {
        sp_uploadDebug(ex);
	}
}

function sp_uploadStart(file) {
	try {
		jQuery( '#' + this.customSettings.progressTarget ).html( file.name );
		sp_swap( this.customSettings.secondButtonId, this.customSettings.cancelButtonId );
	}
	catch (ex) {
		
	}
	return true;
}

function sp_uploadProgress(file, bytesLoaded, bytesTotal) {

	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		jQuery( '#' + this.customSettings.progressTarget ).html( file.name + ' : ' + percent + '%' );
		/*var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");*/
	} catch (ex) {
		sp_uploadDebug(ex);
	}
}

function sp_uploadSuccess(file, serverData) {
	//alert(serverData);
	try {
		jQuery( '#' + this.customSettings.progressTarget ).html( file.name + ' Complete' );
		var cat = jQuery('.docx_options select[name="doc_category"]');
		var lang= jQuery('.docx_options select[name="doc_lang"]');
		jQuery( '#' + this.customSettings.resultTarget ).append(
			jQuery(serverData)
				.find('dd.cat_value')
					.append(cat.clone().val(cat.val()))
				.end()
				/*.find('dd.lang_val')
					.append(lang.clone().val(lang.val()))
				.end()*/
				.find('textarea[name="seo_keywords"]')
					.val(jQuery('.docx_options textarea[name="seo_keys"]').val())
				.end()
				.wrap('<div style="clear: both;" />')
				.parent()
				.prepend('<h2>' + file.name + '</h2>')			
		);
		
		/*var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);*/

	} catch (ex) {
		sp_uploadDebug(ex);
	}
}

function sp_uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued === 0) {
			jQuery( '#' + this.customSettings.progressTarget ).html( 'Add more files' );
			jQuery( 'a.save_all_posts' ).show();
			//sp_swap( this.customSettings.cancelButtonId, this.customSettings.secondButtonId );
		} else {	
			this.startUpload();
		}
	} catch (ex) {
		sp_uploadDebug(ex);
	}

}

function sp_swap( id1, id2 ) {
	jQuery( '#' + id1 ).css('display','none');
	jQuery( '#' + id2 ).css('display','block');
}

function sp_uploadError(file, errorCode, message) {
	try {
		/*var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);*/

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			//progress.setStatus("Upload Error: " + message);
			sp_uploadDebug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			//progress.setStatus("Configuration Error");
			sp_uploadDebug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			//progress.setStatus("Upload Failed.");
			sp_uploadDebug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			//progress.setStatus("Server (IO) Error");
			sp_uploadDebug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			//progress.setStatus("Security Error");
			sp_uploadDebug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			//progress.setStatus("Upload limit exceeded.");
			sp_uploadDebug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
			//progress.setStatus("File not found.");
			sp_uploadDebug("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			//progress.setStatus("Failed Validation.  Upload skipped.");
			sp_uploadDebug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			if (this.getStats().files_queued === 0) {
				//document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			//progress.setStatus("Cancelled");
			//progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			//progress.setStatus("Stopped");
			break;
		default:
			//progress.setStatus("Unhandled Error: " + error_code);
			sp_uploadDebug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        sp_uploadDebug(ex);
    }
}
