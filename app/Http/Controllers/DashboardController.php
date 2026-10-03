<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $habits = auth()->user()->habits()->get();

        $totalHabits = $habits->count();
        $doneToday = $habits->filter(fn ($habit) => $habit->doneToday())->count();
        $bestStreak = $habits->max(fn ($habit) => $habit->currentStreak()) ?? 0;

        return view('dashboard', compact('habits', 'totalHabits', 'doneToday', 'bestStreak'));
    }
}