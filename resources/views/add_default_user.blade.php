use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use RonasIT\Support\Traits\MigrationTrait;

class AddDefaultUser extends Migration
{
    use MigrationTrait;

    public function up()
    {
        if (config('app.env') !== 'testing') {
            DB::table('users')->insert([
                'name' => '{{ $name }}',
                'email' => '{{ $email }}',
                'password' => Hash::make('{{ $password }}'),
                'role_id' => '{{ $role_id }}'
            ]);
        }
    }

    public function down()
    {
        if (config('app.env') !== 'testing') {
            DB::table('users')
                ->where('email', '{{ $email }}')
                ->delete();
        }
    }
}
