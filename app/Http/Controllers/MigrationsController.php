<?php

namespace App\Http\Controllers;

use DB;
use Schema;
use App\UserMigration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigrationsController extends Controller
{
    protected $internalTables = ['pages', 'layouts', 'user_migrations', 'middlewares', 'resources', 'migrations', 'password_resets'];

    public function show()
    {
        $migrations = UserMigration::all();

        return view('migrations.show', ['migrations' => $migrations]);
    }

    public function showCreate()
    {
        $tableColumns = [];

        collect(DB::select('select * from information_schema.columns 
                            where table_schema = \'' . \Config::get("database.connections." . \Config::get('database.default') . ".database") . '\'
                            order by table_name,ordinal_position'))
        ->map(function($table) use (&$tableColumns) {
            $tableColumns[$table->TABLE_NAME][$table->COLUMN_NAME] = ['type' => $table->DATA_TYPE, 'default' => $table->COLUMN_DEFAULT];
        });

        $tables = collect($tableColumns)->filter(function($column, $table) {
            return ! in_array($table, $this->internalTables);
        });

        return view('migrations.create', [
            'tables' => $tables->keys(),
            'all_tables' => collect($tableColumns)->keys(),
            'table_columns' => $tableColumns
        ]);
    }

    public function showEdit(UserMigration $migration)
    {
        return view('migrations.edit', ['migration' => $migration]);
    }

    public function create(Request $request)
    {
        if (Schema::hasTable($request->get('migration_table_name'))) {
            session()->flash('notification', [
                'message' => "Table `{$request->get('migration_table_name')}` already exists!",
                'type' => 'error',
            ]);

            return redirect()->back();
        }

        $run_immediately = $request->get('run_migration') === 'on' ? true : false;
        $class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $request->get('migration_name'))));
        $file_name = date('Y_m_d_' . time() . '_', time()) . $request->get('migration_name') . '.php';

        $migration = $this->generateMigration($request, $class_name, $file_name);

        preg_match('/(?<=create_)(.*?)(?=_table)/', $request->get('migration_name'), $matches);
        $class_name = (preg_replace('/s$/', '', str_replace(' ', '', ucwords(str_replace('_', ' ', $matches[0])))));

        $model = $this->generateModel($request, $class_name, $file_name);

        if ($run_immediately) {
            Artisan::call('migrate:specific', ['files' => [base_path() . '/database/migrations/' . $file_name], '--no-interaction' => true]);
        }

        session()->flash('notification', [
            'message' => 'Migration Created!',
            'type' => 'success',
        ]);

        $userMigration = new UserMigration();

        $userMigration->name = $request->get('migration_name');
        $userMigration->migration = $file_name;
        $userMigration->migrated = $run_immediately;
        $userMigration->table_name = $request->get('migration_table_name');

        $userMigration->save();


        return redirect()->route('migrations.showEdit', ['migration' => $userMigration->id]);
    }

    public function update(UserMigration $migration, Request $request)
    {

    }

    public function generateMigration(Request $request, $class_name, $file_name)
    {
        $migration = <<<MIGRATION
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {$class_name} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{$request->get('migration_table_name')}', function(Blueprint \$table) {
    \$table->increments('id');
MIGRATION;

        foreach($request->get('migration_rows') as $migration_row) {
            if (!is_null($migration_row['migration_default_value']) && strlen(trim($migration_row['migration_default_value'])) > 0) {
                if ($migration_row['migration_name'] === 'id' && $migration_row['migration_type'] === 'integer') {
                    $migration .= <<<MIGRATION
                            
MIGRATION;
                } else {
                    $migration .= <<<MIGRATION
    \$table->{$migration_row['migration_type']}('{$migration_row['migration_name']}')->default('{$migration_row['migration_default_value']}');
        
MIGRATION;
                }
            } else {
                if ($migration_row['migration_name'] === 'id' && $migration_row['migration_type'] === 'integer') {
                    $migration .= <<<MIGRATION
                    
        
MIGRATION;
                } else {
                    $migration .= <<<MIGRATION
    \$table->{$migration_row['migration_type']}('{$migration_row['migration_name']}');
        
MIGRATION;
                }
            }

        }

        $migration .= <<<MIGRATION
    \$table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{$request->get('migration_table_name')}');
    }
}

MIGRATION;


        file_put_contents(base_path() . '/database/migrations/' . $file_name, $migration);
    }

    public function generateModel(Request $request, $class_name)
    {
        $model = <<<MODEL
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class {$class_name} extends Model
{
    
}
MODEL;

        file_put_contents(base_path() . '/app/' . $class_name . '.php', $model);

    }
}
