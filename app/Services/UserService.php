<?php

namespace App\Services;

use App\Jobs\SendMailJob;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
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

    public function create($data) {
        if (!empty($data['role_id']) && ($data['role_id'] == RoleRepository::USER_ROLE)) {
            $data['login'] = $data['email'];
            $data['password'] = bcrypt(uniqid());
        }

        return $this->repository->create($data);
    }

    public function update($where, $data)
    {
        if (empty($data['role_id'])) {
            $data['role_id'] = RoleRepository::USER_ROLE;
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $this->repository->update($where, $data);
    }

    public function forgotPassword($email) {
        $hash = uniqid();

        $this->repository->forceUpdate([
            'email' => $email
        ], [
            'reset_password_hash' => $hash
        ]);

        dispatch(new SendMailJob(
            'emails.forgot_password',
            __('emails.forgot_your_password'),
            $email,
            [
                'hash' => $hash,
                'locale' => session('lang')
            ]
        ));
    }

    public function restorePassword($token, $password) {
        $this->repository->forceUpdate([
            'reset_password_hash' => $token
        ], [
            'password' => bcrypt($password),
            'reset_password_hash' => null
        ]);
    }
}