use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use RonasIT\Support\Traits\MigrationTrait;

class AddDefaultUser extends Migration
{
    use MigrationTrait;

    public function up()
    {
        if (config('app.env') !== 'testing') {
            User::create([
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
            User::where('email', '{{ $email }}')->delete();
        }
    }
}
