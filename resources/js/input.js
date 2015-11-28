$(function () {

    // Initialize file pickers
    $('.files-field_type').each(function () {

        var selected = [];

        var wrapper = $(this);

        var field = wrapper.data('field');

        $('#' + field + '-modal').on('submit', 'form', function (e) {

            e.preventDefault();

            var data = $(this).serializeArray();

            data.shift();

            $.each(data, function (key, input) {
                selected.push(input.value);
            });

            $('[name="entry_' + field + '"]').val(selected.join(','));

            wrapper.find('.selected').load('/streams/files-field_type/selected?uploaded=' + selected.join(','), function () {
                $('#' + field + '-modal').modal('hide');
            });
        });

        $(wrapper).on('click', '[data-dismiss="file"]', function (e) {

            e.preventDefault();

            selected.splice(selected.indexOf($(this).data('file')), 1);

            $('[name="entry_' + field + '"]').val(selected.join(','));

            wrapper.find('.selected').load('/streams/files-field_type/selected?uploaded=' + selected.join(','), function () {
                $('#' + field + '-modal').modal('hide');
            });
        });
    });
});
