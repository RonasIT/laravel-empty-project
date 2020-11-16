<?php

namespace App\Services;

use App\Jobs\SendMailJob;
use App\Mails\ForgotPasswordMail;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
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

    public function search($filters)
    {
        return $this->repository
            ->searchQuery($filters)
            ->filterBy('role_id')
            ->filterByQuery(['name', 'email'])
            ->getSearchResults();
    }

    public function create($data)
    {
        $data['role_id'] = Arr::get($data, 'role_id', Role::USER);
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update($where, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($where, $data);
    }

    public function forgotPassword($email)
    {
        $hash = $this->generateHash();

        $this->repository
            ->force()
            ->update([
                'email' => $email
            ], [
                'set_password_hash' => $hash,
                'password_hash_created_at' => Carbon::now()
            ]);

        $mail = new ForgotPasswordMail($email, ['hash' => $hash]);
        dispatch(new SendMailJob($mail));
    }

    public function restorePassword($token, $password)
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

    protected function generateHash($length = 32)
    {
        $length /= 2;

        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}
