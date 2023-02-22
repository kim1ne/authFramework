<?php

namespace App\Models;

use App\Services\Crypt;
use Zend\Diactoros\ServerRequestFactory;

class Photo extends ActiveRecord
{
    protected $photo;
    protected $user_id;

    public static function getTableName(): string
    {
        return 'photos';
    }
}