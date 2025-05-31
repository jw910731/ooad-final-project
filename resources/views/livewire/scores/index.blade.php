<?php

use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{booted, layout, mount, state};

layout('components.layouts.course');

state(['course']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

mount(function (Course $course) {
    $this->course = $course;
    //dd($course->scores()->get());
});

?>
    <flux:container>
        @can('update', $course)
            <flux:button :href="route('score.create',[$course->id])">Add</flux:button>
        @endcan
        @foreach($course->scores()->get() as $score)
            <a href="{{route('score.show', [$course, $score])}}">
                <x-card class="flex-auto flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $score->title }}</flux:heading>
                    <flux:text class="mt-2">{{ $score->description }}</flux:text>
                </x-card>
            </a>
        @endforeach
    </flux:container>
