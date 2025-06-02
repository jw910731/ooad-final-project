<?php

use App\Models\Course;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;
use function Livewire\Volt\{state};

state(['courses' => fn() => auth()->user()->courses()->get()]);

//courseFocused = 1;

$delete_course = function ( Course $target): void
{
    //$target->users()->score()->delete();
    //dd($target);
    $target->users()->detach();
    $target->delete();
}
?>

<section class="w-full">
    @can('create', App\Models\Course::class)
        <flux:button :href="route('courses.create')">Add</flux:button>
    @endcan
    <flux:container class="flex">
        @foreach($courses as $course)
            <a
               href="{{route('courses.show', $course)}}">
                <x-card class="flex m-6 flex m-6"
{{--                        wire:mouseover="$set('courseFocused',{{$course}})"--}}>
                    <flux:heading class="flex items-center gap-2">{{ $course->title }}</flux:heading>
                    <flux:text class="mt-2">{{ $course->description }}</flux:text>
                    @can('delete', $course)
                            <x-button negative wire:click="delete_course({{$course}})" href="{{route('courses.index')}}">
                                {{ __('Delete') }}
                            </x-button>
                    @endcan
                </x-card>
            </a>
        @endforeach
    </flux:container>
{{--    @if($courseFocused)--}}
{{--    <x-card class="flex m-6 flex m-6" wire:mouseover="$refresh()">--}}
{{--        <flux:heading class="flex items-center gap-2">{{ $courseFocused->title }}</flux:heading>--}}
{{--        <flux:text class="mt-2">{{ $courseFocused->description }}</flux:text>--}}
{{--    </x-card>--}}
{{--    @endif--}}
</section>
