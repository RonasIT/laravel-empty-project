<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollectionResource extends ResourceCollection
{
    public $collects = UserResource::class;
}
