<?php
namespace App\Tests\Support;

trait AuthTestTrait {
    public function getLoginFilters()
    {
        return [
            [
                'email' => 'fidel.kutch@example.com',
                'password' => 'wrong password'
            ],
            [
                'email' => 'wrong login',
                'password' => 'wrong password'
            ]
        ];
    }
}