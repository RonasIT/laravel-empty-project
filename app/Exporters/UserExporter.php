<?php

namespace App\Exporters;

use App\Models\User;
use RonasIT\Support\Exporters\Exporter;

class UserExporter extends Exporter
{
    public function __construct()
    {
        $this->fields = User::getFields();
    }
}
