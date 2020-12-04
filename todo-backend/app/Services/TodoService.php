<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\User;

class TodoService
{

    public function createTodo($newTodo, User $user) {
        $todo = new Todo();
        $todo->title = $newTodo['title'];
        $todo->description = $newTodo['description'] ?? NULL;
        $todo->priority = $newTodo['priority'];
        $todo->user_id = $user->id;
        $todo->completed = $newTodo['completed'] ?? false;
        $todo->save();
        return $todo;
    }

    public function updateTodo($todo, $newData) {
        $todo->title = $newData['title'] ?? $todo->title;
        $todo->description = $newData['description'] ?? $todo->description;
        $todo->priority = $newData['priority'] ?? $todo->priority;
        $todo->completed = $newData['completed'] ?? $todo->completed;
        $todo->save();
        return $todo;
    }
}
