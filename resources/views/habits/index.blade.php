<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Habits') }}
            </h2>
            <a href="{{ route('habits.create') }}">
                <x-primary-button type="button">{{ __('New Habit') }}</x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="font-medium text-sm text-green-600">{{ session('status') }}</div>
            @endif

            @forelse ($habits as $habit)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $habit->color }}"></span>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $habit->name }}</div>
                        @if ($habit->description)
                            <div class="text-sm text-gray-600">{{ $habit->description }}</div>
                        @endif
                    </div>
                    <a href="{{ route('habits.edit', $habit) }}" class="text-sm text-indigo-600 underline">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('habits.destroy', $habit) }}" onsubmit="return confirm('Delete this habit?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 underline">{{ __('Delete') }}</button>
                    </form>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>