<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Enumerations\Priorities;
use App\Models\Todo;


class TodoController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Todo::class, 'todo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Auth::user()->todos;
        return response()->json([$todos], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(array('priority' => strtolower($request->input('priority'))));
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(Priorities::$types)],
            'completed' => ['nullable', 'boolean']
        ]);

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description ?? NULL;
        $todo->priority = $request->priority;
        $todo->user_id = $request->user()->id;
        $todo->completed = $request->completed ?? false;
        $todo->save();
        return response()->json($todo, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json($todo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        if ($request->priority != NULL) {
            $request->merge(array('priority' => strtolower($request->input('priority'))));
        }

        $request->validate([
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', Rule::in(Priorities::$types)],
            'completed' => ['nullable', 'boolean']
        ]);

        $todo->title = $request->title ?? $todo->title;
        $todo->description = $request->description ?? $todo->description;
        $todo->priority = $request->priority ?? $todo->priority;
        $todo->user_id = $request->user()->id;
        $todo->completed = $request->completed ?? $todo->completed;
        $todo->save();
        return response()->json($todo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json($todo, 200);
    }
}
