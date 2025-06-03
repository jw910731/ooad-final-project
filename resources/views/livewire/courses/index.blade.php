<?php

use App\Models\Course;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;
use function Livewire\Volt\{state};

state(['courses' => fn() => auth()->user()->courses()->get()]);

$delete_course = function ( Course $target): void
{
    //Todo: remove relative assignment and scores;
    $target->users()->detach();
    $target->delete();
    $this->redirect('courses.index');
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
                <x-card class="flex m-6 flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $course->title }}</flux:heading>
                    <flux:text class="mt-2">{{ $course->description }}</flux:text>
                    @can('delete', $course)
                            <flux:button icon="trash"
                                         variant="danger"
                                      wire:click="delete_course({{$course}})"
                            >Delete
                            </flux:button>
                    @endcan
                </x-card>
            </a>
        @endforeach
    </flux:container>
</section>
