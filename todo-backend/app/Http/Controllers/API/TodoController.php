<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodo;
use App\Http\Requests\UpdateTodo;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Services\TodoService;

class TodoController extends Controller
{

    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->authorizeResource(Todo::class, 'todo');
        $this->todoService = $todoService;
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
    public function store(StoreTodo $request)
    {
        // priority to lower case
        $request->merge(array('priority' => strtolower($request->input('priority'))));
        // validate request -> returns array with validated elements from request
        $validated = $request->validated();
        // push id of authenticated user to the validated array and pass it to service
        $validated = array_merge($validated, ['user_id' => $request->user()->id]);
        // creates new todo item and saves it to the DB
        $todo = $this->todoService->createTodo($validated);

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
    public function update(UpdateTodo $request, Todo $todo)
    {
        // priority to lower case
        if ($request->priority != NULL) {
            $request->merge(array('priority' => strtolower($request->input('priority'))));
        }
        // validate
        $validated = $request->validated();
        // pass to service
        $this->todoService->updateTodo($todo, $validated);

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
