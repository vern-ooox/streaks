<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">{{ __('Total habits') }}</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $totalHabits }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">{{ __('Done today') }}</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $doneToday }} / {{ $totalHabits }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">{{ __('Best current streak') }}</div>
                    <div class="text-3xl font-semibold text-gray-900">{{ $bestStreak }} {{ __('day(s)') }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Habit report') }}</h3>

                @if ($habits->isEmpty())
                    <p class="text-gray-600">{{ __('No habits yet.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-gray-500 border-b">
                                    <th class="py-2 pr-4">{{ __('Habit') }}</th>
                                    <th class="py-2 pr-4">{{ __('Current streak') }}</th>
                                    <th class="py-2 pr-4">{{ __('Longest streak') }}</th>
                                    <th class="py-2 pr-4">{{ __('Last 30 days') }}</th>
                                    <th class="py-2">{{ __('Completion') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($habits as $habit)
                                    @php
                                        $count = $habit->checkInsLast30Days();
                                        $rate = round($count / 30 * 100);
                                    @endphp
                                    <tr class="border-b last:border-0">
                                        <td class="py-3 pr-4">
                                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $habit->color }}"></span>
                                            {{ $habit->name }}
                                        </td>
                                        <td class="py-3 pr-4">{{ $habit->currentStreak() }}</td>
                                        <td class="py-3 pr-4">{{ $habit->longestStreak() }}</td>
                                        <td class="py-3 pr-4">{{ $count }} / 30</td>
                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-2 rounded-full" style="width: {{ $rate }}%; background-color: {{ $habit->color }}"></div>
                                                </div>
                                                <span>{{ $rate }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>