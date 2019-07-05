<?php

namespace App\Services;

use App\Jobs\SendMailJob;
use App\Mails\ForgotPasswordMail;
use Illuminate\Support\Arr;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Hash;
use RonasIT\Support\Services\EntityService;

/**
 * @property  UserRepository $repository
 */
class UserService extends EntityService
{
    protected $roleRepository;

    public function __construct()
    {
        $this->setRepository(UserRepository::class);
    }

    public function create($data)
    {
        $data['role_id'] = Arr::get($data, 'role_id', RoleRepository::USER_ROLE);
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update($where, $data)
    {
        if (empty($data['role_id'])) {
            $data['role_id'] = RoleRepository::USER_ROLE;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($where, $data);
    }

    public function forgotPassword($email)
    {
        $hash = $this->generateUniqueHash();

        $this->repository
            ->force()
            ->update([
                'email' => $email
            ], [
                'reset_password_hash' => $hash
            ]);

        $mail = new ForgotPasswordMail($email, ['hash' => $hash]);
        dispatch(new SendMailJob($mail));
    }

    public function restorePassword($token, $password)
    {
        $this->repository
            ->force()
            ->update([
                'reset_password_hash' => $token
            ], [
                'password' => bcrypt($password),
                'reset_password_hash' => null
            ]);
    }

    protected function generateUniqueHash($length = 16)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}
