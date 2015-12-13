$(function () {

    // Initialize file pickers
    $('.files-field_type').each(function () {

        var wrapper = $(this);
        var field = wrapper.data('field');
        var modal = $('#' + field + '-modal');

        var selected = $('[name="' + field + '"]').val().split(',');

        modal.on('click', '[data-file]', function (e) {

            e.preventDefault();

            selected.push($(this).data('file'));

            $('[name="' + field + '"]').val(selected.join(','));

            $(this).closest('tr').addClass('success').fadeOut();

            wrapper.find('.selected').load(APPLICATION_URL + '/streams/files-field_type/selected?uploaded=' + selected.join(','));
        });

        $(wrapper).on('click', '[data-dismiss="file"]', function (e) {

            e.preventDefault();

            selected.splice(selected.indexOf($(this).data('file')), 1);

            $('[name="' + field + '"]').val(selected.join(','));

            wrapper.find('.selected').load(APPLICATION_URL + '/streams/files-field_type/selected?uploaded=' + selected.join(','), function () {
                $('#' + field + '-modal').modal('hide');
            });
        });
    });
});
