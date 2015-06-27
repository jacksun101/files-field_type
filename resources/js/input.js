$(function () {

    // Initialize uploaders
    $('.files-field-type .dropzone').each(function () {

        // Configure Dropzone
        var dropzone = $(this).dropzone({
            url: '/streams/files-field_type/upload',
            paramName: 'upload',
            dictDefaultMessage: $(this).data('message'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            sending: function (file, xhr, formData) {
                formData.append('disk', dropzone.data('disk'));
                formData.append('path', dropzone.data('path'));
            }
        });
    });
});
