<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Habit extends Model
{
    protected $fillable = ['name', 'description', 'color'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function doneToday(): bool
    {
        return $this->logs()->whereDate('completed_on', today())->exists();
    }

    public function currentStreak(): int
    {
        $dates = $this->logs()
            ->pluck('completed_on')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->all();

        $day = today();

        if (! in_array($day->toDateString(), $dates)) {
            $day = $day->copy()->subDay();
        }

        $streak = 0;

        while (in_array($day->toDateString(), $dates)) {
            $streak++;
            $day = $day->copy()->subDay();
        }

        return $streak;
    }

    public function longestStreak(): int
    {
        $dates = $this->logs()
            ->orderBy('completed_on')
            ->pluck('completed_on')
            ->map(fn ($d) => Carbon::parse($d)->startOfDay())
            ->values();

        $longest = 0;
        $run = 0;
        $previous = null;

        foreach ($dates as $date) {
            if ($previous && $previous->copy()->addDay()->equalTo($date)) {
                $run++;
            } else {
                $run = 1;
            }

            $longest = max($longest, $run);
            $previous = $date;
        }

        return $longest;
    }

    public function checkInsLast30Days(): int
    {
        return $this->logs()
            ->whereDate('completed_on', '>=', today()->subDays(29))
            ->count();
    }
}