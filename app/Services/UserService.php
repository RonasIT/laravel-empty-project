<?php

namespace App\Services;

use App\Mail\ForgotPasswordMail;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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

    public function forgotPassword(string $email): string
    {
        return Password::sendResetLink(
            credentials: ['email' => $email],
            callback: fn ($user, $token) =>
                Mail::to($user->email)->send(new ForgotPasswordMail(['hash' => $token]))
        );
    }

    public function restorePassword(array $credentials): string
    {
        return Password::reset(
            credentials: $credentials,
            callback: fn (User $user, string $password) =>
                $this->repository
                    ->force()
                    ->update($user->id, [
                        'password' => Hash::make($password),
                    ])
        );
    }
}
