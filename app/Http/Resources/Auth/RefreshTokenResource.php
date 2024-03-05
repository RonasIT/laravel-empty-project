<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseJsonResource;
use Symfony\Component\HttpFoundation\Cookie;

class RefreshTokenResource extends BaseJsonResource
{
    public function __construct(
        protected string $newToken,
        protected Cookie $tokenCookie,
    ) {
        parent::__construct([]);
    }

    public function toArray($request): array
    {
        return [
            'token' => $this->newToken,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->header('Authorization', "Bearer {$this->newToken}");
        $response->cookie($this->tokenCookie);
    }
}
