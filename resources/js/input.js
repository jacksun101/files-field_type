$(function () {

    // Initialize uploaders
    $('.files-field-type .dropzone').each(function () {

        // Configure Dropzone
        $(this).dropzone({
            url: 'admin/files/upload',
            paramName: 'file',
            dictDefaultMessage: DROPZONE_MESSAGE,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            sending: function (file, xhr, formData) {
                formData.append('disk', DROPZONE_DISK);
                formData.append('folder', DROPZONE_FOLDER);
            }
        });
    });
});
