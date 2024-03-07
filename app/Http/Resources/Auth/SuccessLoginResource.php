<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseJsonResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Cookie;

class SuccessLoginResource extends BaseJsonResource
{
    public function __construct(
        protected string $token,
        protected User $user,
        protected Cookie $tokenCookie,
    ) {
        parent::__construct([]);
    }

    public function toArray($request): array
    {
        return [
            'token' => $this->token,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
            'user' => $this->user,
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->cookie($this->tokenCookie);
    }
}
