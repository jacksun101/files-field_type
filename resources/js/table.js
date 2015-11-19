$(function () {

    // Radios and checkboxes
    $(':checkbox').radiocheck();
    $(':radio').radiocheck();

    // Initialize file selectors.
    $('form.ajax').submit(function (e) {

        e.preventDefault();

        var table = $(this).find('table');

        $.each($(this).serializeArray(), function (key, input) {

            if (input.name == 'id[]') {
                $('input[name="' + table.attr('id') + '"]').tagsinput('add', {
                    id: input.value,
                    filename: 'File # ' + input.value
                });
            }
        });

        $('.modal').modal('hide');
    });
});
