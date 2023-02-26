<?php

namespace App\Models;

class Category extends ActiveRecord
{
    protected string $category_name;
    protected $user_id;

    public static function getTableName(): string
    {
        return 'categories';
    }
}