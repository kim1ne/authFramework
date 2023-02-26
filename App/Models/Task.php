<?php

namespace App\Models;

class Task extends ActiveRecord
{
    protected string $task_name;
    protected int $category_id;
    protected int $status;
    protected int $user_id;

    public function updateStatus()
    {
        $this->status = ($this->status) ? 0 : 1;
    }

    public static function getTableName(): string
    {
        return 'tasks';
    }
}