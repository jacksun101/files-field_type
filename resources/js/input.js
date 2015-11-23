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

        var items = [];

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

            items[value.id] = value;

            input.next('.bootstrap-tagsinput').find('.tag').last().attr('data-file', value.id);
        });

        var dragged = '';

        input.next('.bootstrap-tagsinput').sortable({
            itemSelector: '.tag',
            //itemPath: '> span'
            //containerSelector: 'table',
            placeholder: '<span class="placeholder tag label label-info"/>',
            afterMove: function ($placeholder) {

                dragged = $placeholder.closest('.bootstrap-tagsinput').find('.dragged').detach();

                $placeholder.html(dragged.html());
            },
            onDrop: function ($item, container, _super, event) {

                var newValues = [];

                value = input.tagsinput('items');

                console.log(items);

                input.next('.bootstrap-tagsinput').find('.tag').each(function () {
                    newValues.push(items[$(this).data('file')]);
                });

                console.log(newValues);

                input.tagsinput('removeAll');

                $.each(newValues, function(key, newValue) {

                    input.tagsinput('add', newValue);

                    input.next('.bootstrap-tagsinput').find('.tag').last().attr('data-file', newValue.id);
                });

                _super($item, container, _super, event);
            }
        });
    });
});
