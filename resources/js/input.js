$(function () {

    // Initialize file pickers
    $('.files-field_type').each(function () {

        var wrapper = $(this);
        var field = wrapper.data('field');
        var modal = $('#' + field + '-modal');

        var selected = $('[name="' + field + '"]').val().split(',');

        wrapper.sort = function() {
            wrapper.find('table').sortable({
                handle: '.handle',
                itemSelector: 'tr',
                itemPath: '> tbody',
                containerSelector: 'table',
                placeholder: '<tr class="placeholder"/>',
                afterMove: function ($placeholder) {

                    $placeholder.closest('table').find('button.reorder').removeClass('disabled');

                    $placeholder.closest('table').find('.dragged').detach().insertBefore($placeholder);

                    selected = [];

                    $(wrapper.find('table').find('[data-dismiss="file"]')).each(function() {
                        selected.push(String($(this).data('file')));
                    });

                    $('[name="' + field + '"]').val(selected.join(','));
                }
            });
        };

        wrapper.sort();

        modal.on('click', '[data-file]', function (e) {

            e.preventDefault();

            selected.push(String($(this).data('file')));

            $('[name="' + field + '"]').val(selected.join(','));

            $(this).closest('tr').addClass('success').fadeOut();

            wrapper.find('.selected').load(
                APPLICATION_URL + '/streams/files-field_type/selected?uploaded=' + selected.join(','),
                function() {
                    wrapper.sort();
                }
            );
        });

        $(wrapper).on('click', '[data-dismiss="file"]', function (e) {

            e.preventDefault();

            selected.splice(selected.indexOf(String($(this).data('file'))), 1);

            $('[name="' + field + '"]').val(selected.join(','));

            $(this).closest('tr').addClass('danger').fadeOut();
        });
    });
});
