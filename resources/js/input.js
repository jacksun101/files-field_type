$(function () {

    // Initialize file pickers
    $('.files-field_type').each(function () {

        var wrapper = $(this);
        var field = wrapper.data('field');

        $('#' + field + '-modal').on('submit', 'form', function (e) {

            e.preventDefault();

            var selected = [];

            var data = $(this).serializeArray();

            data.shift();

            $.each(data, function (key, input) {
                selected.push(input.value);
            });

            wrapper.find('.selected').load('/streams/files-field_type/selected?uploaded=' + selected.join(','), function () {
                $('#' + field + '-modal').modal('hide');
            });
        });
    });
});
