$(function () {

    // Initialize tag inputs.
    $('.files-field-type input.form-control').each(function () {

        var config = {
            itemValue: 'id',
            itemText: 'filename',
            allowDuplicates: false,
            freeInput: false
        };

        var source = $(this).data('source');
        var options = $(this).data('options');

        if (source || options) {

            config.typeahead = {
                minLength: 0,
                displayText: function (item) {
                    return item;
                },
                source: options ? options.split(',') : source
            };

            config.freeInput = $(this).data('allow_creating_tags');
        }

        var input = $(this);

        input.tagsinput(config);

        $.each(JSON.parse($(this).val()), function (key, value) {
            input.tagsinput('add', value);
        });
    });
});
