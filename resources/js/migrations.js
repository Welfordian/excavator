class Migrations {
    constructor(config) {
        this.create = require('./Migrations/create').default(config);
        this.update = require('./Migrations/update').default(config);
        this.drop = require('./Migrations/drop').default(config);

        $('form').bind('submit', function () {
            $(this).find(':input').prop('disabled', false);
        });

        $(document).on('click', '#add_incrementing_id', function(e) {
            e.preventDefault();
        });

        $(document).on('click', '#add_timestamps', function(e) {
            e.preventDefault();
        });

        $(document).on('change', '.migration_type', function () {
            let selected = $(this).find(':selected');

            if (selected.attr('data-default-allowed') === "true") {
                $(this).parent().parent().find('.migration_default_value').prop('disabled', false);
            } else {
                $(this).parent().parent().find('.migration_default_value').prop('disabled', true);
            }
        });

        $(document).on('click', '.remove_migration_row', function () {
            let type = $('.pane-link.active').attr('href').replace('#pills-', '');

            $(this).parent().parent().remove();

            if ($('#migration_rows-' + type).find('.migration_row').length === 0) {
                $('.create_migration').addClass('hidden');
            }
        });

        $(document).on('click', '.pane-link', function () {
            let type = $('.pane-link.active').attr('href').replace('#pills-', '');

            $('[name="migration_method"]').val(type);

            if (type === 'create') {
                if ($('#migration_rows-create').find('.migration_row').length === 0) {
                    $('.create_migration').addClass('hidden');
                } else {
                    $('.create_migration').removeClass('hidden');
                }
            }

            if (type === 'update') {
                if ($('#migration_rows-update').find('.migration_row').length === 0) {
                    $('.create_migration').addClass('hidden');
                } else {
                    $('.create_migration').removeClass('hidden');
                }
            }

            if (type === 'drop') {
                $('.create_migration').removeClass('hidden');
            }

            window.location.hash = type;
        });

        if (! /(update):(.*)/.test(window.location.hash)) {
            if (window.location.hash === '#create' || window.location.hash === '#update' || window.location.hash === '#drop') {
                $('#pills-' + window.location.hash.replace('#', '') + '-tab').click();
            }
        }

    }
}

export default function(config) {
    return new Migrations(config)
}
