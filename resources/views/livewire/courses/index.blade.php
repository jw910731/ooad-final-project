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
    <flux:heading class="flex-1 items-center gap-2" style="font-size:14pt">
        Courses
    </flux:heading>
    <div class="px-0">
        @foreach($courses as $course)
            <a href="{{route('courses.show', $course)}}" class="m-0">
                <x-card class="flex my-6">
                    <div class="ml-4">
                        <div class="flex">
                            <flux:heading class="flex-1 items-center gap-2" style="font-size:14pt">
                                {{ $course->title }}
                            </flux:heading>
                            @can('delete', $course)
                                <flux:button
                                    variant="danger"
                                    wire:click="delete_course({{$course}})"
                                >
                                    <x-icon name="trash"></x-icon>
                                </flux:button>
                            @endcan
                        </div>
                        <flux:text class="mx-6">{!! nl2br(e($course->description)) !!}</flux:text>
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>
    @can('create', App\Models\Course::class)
        <flux:button :href="route('courses.create')">Add</flux:button>
    @endcan
</section>
