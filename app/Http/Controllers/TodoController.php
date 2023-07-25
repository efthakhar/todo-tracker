<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // return all todo items of a specific user
    public function index(Request $request)
    {

        $user_id = $request->header('id');

        return Todo::where('user_id', $user_id)->get();
    }

    // return specific todo item
    public function show(Request $request, $id)
    {

        $user_id = $request->header('id');

        return Todo::where('user_id', $user_id)->where('id', $id)->first();
    }

    // create todo item
    public function store(Request $request)
    {

        $user_id = $request->header('id');

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'details' => 'nullable',
            'status' => 'nullable',
            'deadline' => 'date|nullable',
        ]);

        return Todo::create([
            'title' => $validatedData['title'],
            'user_id' => $user_id,
            'details' => $validatedData['details'],
            'status' => $validatedData['status'],
            'deadline' => $validatedData['deadline'],
        ]);
    }

    // update todo item
    public function update(Request $request, $id)
    {

        $user_id = $request->header('id');

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'details' => 'nullable',
            'status' => 'nullable',
            'deadline' => 'date|nullable',
        ]);

        return Todo::where('id', $id)->where('user_id', $user_id)->update([
            'title' => $validatedData['title'],
            'details' => $validatedData['details'],
            'status' => $validatedData['status'],
            'deadline' => $validatedData['deadline'],
        ]);
    }

    // delete todo item
    public function delete(Request $request, $id)
    {

        $user_id = $request->header('id');

        return Todo::where('id', $id)->where('user_id', $user_id)->delete();
    }
}
