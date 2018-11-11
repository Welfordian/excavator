export default function({all_tables}) {
    return function() {
        let index = 0;

        $(document).on('click', '#add_migration_row-create', function (e) {
            e.preventDefault();

            let new_migration_row = $('#migration_row_template').clone();

            $(new_migration_row).find('.migration_type').attr('name', `migration_rows[${index}][migration_type]`);
            $(new_migration_row).find('.migration_name').attr('name', `migration_rows[${index}][migration_name]`);
            $(new_migration_row).find('.migration_default_value').attr('name', `migration_rows[${index}][migration_default_value]`);

            $('#migration_rows-create').append(new_migration_row.html());

            $('.create_migration').removeClass('hidden');

            index++;
        });

        $(document).on('keyup', '.migration_table_name-create', function () {
            let table_name = $(this).val().trim();

            if (/^\w+$/.test(table_name)) {
                if (table_name.length) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                if (all_tables.indexOf(table_name) !== -1) {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                }
            } else {
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
            }
        });

        $(document).on('keyup', '.migration_name-create', function () {
            let user_input = $(this).val().trim();

            if (user_input.length) {
                if (/^\w+$/.test(user_input)) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');

                    if (/(?<=create_)(.*?)(?=_table)/.test(user_input)) {
                        let table_name = /(?<=create_)(.*?)(?=_table)/.exec(user_input)[0];

                        if (all_tables.indexOf(table_name) !== -1) {
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).addClass('is-valid');
                        }

                        $('.migration_table_name-create').val(table_name);
                        $('.migration_table_name-create').trigger('keyup');
                        $('.migration_table_name-create').prop('readonly', true);
                    } else {
                        $('.migration_table_name-create').prop('readonly', false);
                    }
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }
            } else {
                $(this).addClass('is-invalid');
                $('.migration_table_name-create').prop('readonly', false);
            }
        });

        return this;
    }
}
