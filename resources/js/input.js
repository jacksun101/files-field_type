$(function () {

    // Initialize uploaders.
    $('.files-field-type').each(function () {

        var wrapper = $(this);
        var template = wrapper.find('.template');
        var preview = template.html();

        template.remove();

        var myDropzone = new Dropzone('[data-field="' + wrapper.data('field') + '"]',
            {
                paramName: 'upload',
                url: '/streams/files-field_type/upload',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                sending: function (file, xhr, formData) {
                    formData.append('disk', 1);
                    formData.append('path', 'Test Folder/Pages');
                },

                autoQueue: true,
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 20,
                previewTemplate: preview,
                maxFilesize: wrapper.find('.files').data('max'),
                previewsContainer: '[data-field="' + wrapper.data('field') + '"]',
                clickable: '[data-field="' + wrapper.data('field') + '"] [data-action="upload-files"]'
            }
        );

        var types = types = wrapper.find('.files').data('mimes');

        if (types.length > 0) {
            myDropzone.options.acceptedFiles = types.split(',');
        }

        myDropzone.on('addedfile', function (file) {
            /*file.previewElement.querySelector('[data-action="upload-file"]').onclick = function () {
             myDropzone.enqueueFile(file);
             };*/
        });

        // Update the total progress bar
        myDropzone.on('totaluploadprogress', function (progress) {
            wrapper.find('[data-progress="total"] .progress-bar').css('width', progress + '%');
        });

        // While file is in transit...
        myDropzone.on('sending', function (file) {

            // Update the progress bar when sending.
            wrapper.find('[data-progress="total"]').css('visibility', 'visible');

            // If a preview is not possible - use no-preview.
            var images = ['jpeg', 'jpg', 'png', 'bmp', 'gif'];
            var regex = /(?:\.([^.]+))?$/;
            var extension = regex.exec(file.name)[1];

            extension = extension.toLowerCase();

            if (images.indexOf(extension) == -1) {
                file.previewElement.querySelector('a.preview').className += ' no-preview';
            }

            // And disable the start button.
            /*file.previewElement.querySelector('[data-action="upload-file"]').setAttribute('disabled', 'disabled');*/

            // Reveal file upload progress.
            file.previewElement.querySelector('[data-progress="file"]').setAttribute('style', 'visibility: visible;');
        });

        // When file successfully uploads.
        myDropzone.on('success', function (file) {

            var response = JSON.parse(file.xhr.response);

            file.previewElement.querySelector('[data-progress="file"]').setAttribute('style', 'visibility: hidden;');

            // Set the preview link's href attribute.
            file.previewElement.querySelector('a.preview').setAttribute('href', APPLICATION_URL + '/streams/files-field_type/view/' + response.id);

            // Set the input's value so when we save it attaches.
            file.previewElement.querySelector('input').setAttribute('value', response.id);
        });

        // Hide the progress bar when done.
        myDropzone.on('queuecomplete', function (progress) {
            wrapper.find('[data-progress="total"]').css('visibility', 'hidden');
        });
    });
});
