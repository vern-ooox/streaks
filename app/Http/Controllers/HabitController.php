<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        $habits = auth()->user()->habits()->latest()->get();

        return view('habits.index', compact('habits'));
    }

    public function create()
    {
        return view('habits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $request->user()->habits()->create($data);

        return redirect()->route('habits.index')->with('status', 'Habit created.');
    }

    public function edit(Habit $habit)
    {
        $this->authorizeHabit($habit);

        return view('habits.edit', compact('habit'));
    }

    public function update(Request $request, Habit $habit)
    {
        $this->authorizeHabit($habit);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $habit->update($data);

        return redirect()->route('habits.index')->with('status', 'Habit updated.');
    }

    public function destroy(Habit $habit)
    {
        $this->authorizeHabit($habit);

        $habit->delete();

        return redirect()->route('habits.index')->with('status', 'Habit deleted.');
    }

    private function authorizeHabit(Habit $habit): void
    {
        abort_if($habit->user_id !== auth()->id(), 403);
    }
}