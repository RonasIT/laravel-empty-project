<?php

namespace App\Services;

use App\Mails\ForgotPasswordMail;
use App\Models\Role;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RonasIT\Support\Services\EntityService;

/**
 * @property UserRepository $repository
 * @mixin UserRepository
 */
class UserService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(UserRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        return $this
            ->searchQuery($filters)
            ->filterByQuery(['name', 'email'])
            ->getSearchResults();
    }

    public function create(array $data): Model
    {
        $data['role_id'] = Arr::get($data, 'role_id', Role::USER);
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update($where, array $data): Model
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($where, $data);
    }

    public function forgotPassword(string $email): void
    {
        $hash = $this->generateHash();

        $this->repository
            ->force()
            ->update([
                'email' => $email
            ], [
                'set_password_hash' => $hash,
                'set_password_hash_created_at' => Carbon::now()
            ]);

        Mail::to($email)->send(new ForgotPasswordMail(['hash' => $hash]));
    }

    public function restorePassword(string $token, string $password): void
    {
        $this->repository
            ->force()
            ->update([
                'set_password_hash' => $token
            ], [
                'password' => Hash::make($password),
                'set_password_hash' => null
            ]);
    }

    /**
     * @codeCoverageIgnore
     */
    protected function generateHash(int $length = 32): string
    {
        $length /= 2;

        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}
