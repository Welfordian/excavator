class MigrationUpdate {
    constructor({all_tables, table_columns}) {
        let self = this;

        if (/(update):(.*)/.test(window.location.hash)) {
            let hash = window.location.hash;
            $('#pills-update-tab').click();
            $('.migration_table_name-update').find('[value="' + /(update):(.*)/.exec(hash.replace('#', ''))[2] + '"]').attr('selected', 'selected');
        }

        this.index = 0;
        this.all_tables = all_tables;
        this.table_columns = table_columns;

        this.typeMap = {
            'int': 'integer',
            'string': 'string',
            'varchar': 'string',
            'timestamp': 'timestamp'
        };

        this.renderTableColumns();

        return function() {
            $(document).on('click', '#add_migration_row-update', function (e) {
                e.preventDefault();

                self.addMigrationRow();
            });

            $(document).on('keyup', '.migration_name-update', function () {
                let table_name = false;
                let user_input = $(this).val();

                if (/(?<=from_).*/.test(user_input)) {
                    table_name = /(?<=from_).*/.exec(user_input)[0];
                }

                if (/(?<=from_)(.*?)(?=_table)/.test(user_input)) {
                    table_name = /(?<=from_)(.*?)(?=_table)/.exec(user_input)[0];
                }

                if (/(?<=to_).*/.test($(this).val())) {
                    table_name = /(?<=to_).*/.exec(user_input)[0];
                }

                if (/(?<=to_)(.*?)(?=_table)/.test(user_input)) {
                    table_name = /(?<=to_)(.*?)(?=_table)/.exec(user_input)[0];
                }

                if (table_name) {
                    if ($('.migration_table_name-update').find('[value="' + table_name + '"]').length) {
                        $('.migration_table_name-update').val(table_name);
                        $('.migration_table_name-update').prop('disabled', true);
                    } else {
                        $('.migration_table_name-update').prop('disabled', false);
                    }
                } else {
                    $('.migration_table_name-update').prop('disabled', false);
                }
            });

            $(document).on('change', '.migration_table_name-update', function () {
                $('#migration_rows-update').html("");

                window.location.hash = "update:" + $('.migration_table_name-update').find(':selected').val();

                self.renderTableColumns();
            });

            return this;
        }
    }

    renderTableColumns() {
        let selected = $('.migration_table_name-update').find(':selected').val();

        Object.keys(this.table_columns[selected]).forEach((key) => {
            this.addMigrationRow({
               migration_type: this.table_columns[selected][key]['type'],
               migration_name: key,
               migration_default_value: this.table_columns[selected][key]['default']
            });
        });
    }

    addMigrationRow(values = false) {
        let new_migration_row = $('#migration_row_template').clone();

        if (values) {
            console.log(values);
            $(new_migration_row).find('.migration_type').attr('name', `migration_rows[${this.index}][migration_type]`).find('[value="' + this.typeMap[values.migration_type] + '"]').attr('selected', 'selected');
            $(new_migration_row).find('.migration_name').attr('name', `migration_rows[${this.index}][migration_name]`).attr('value', values.migration_name);
            $(new_migration_row).find('.migration_default_value').attr('name', `migration_rows[${this.index}][migration_default_value]`).attr('value', values.migration_default_value);
        } else {
            $(new_migration_row).find('.migration_type').attr('name', `migration_rows[${this.index}][migration_type]`);
            $(new_migration_row).find('.migration_name').attr('name', `migration_rows[${this.index}][migration_name]`);
            $(new_migration_row).find('.migration_default_value').attr('name', `migration_rows[${this.index}][migration_default_value]`);
        }

        $('#migration_rows-update').append(new_migration_row.html());

        $('.create_migration').removeClass('hidden');

        this.index++;
    }
}

export default function(config) {
    return new MigrationUpdate(config);
}
