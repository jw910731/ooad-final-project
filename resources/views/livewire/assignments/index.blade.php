<?php

//use function Livewire\Volt\{state};

use App\Models\Assignment;
use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{state, booted, mount, layout};

layout('components.layouts.course');

state(['course', 'assignments']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

mount(function (Course $course) {
    $this->course = $course;
    $this->assignments = $course->assignments;
});
?>

<section class="w-full">
    @if(auth()->user()->system_admin)
        <flux:button :href="route('assignment.create',$course)">Add</flux:button>
    @endif
    <flux:container class="flex">
        @foreach($assignments as $assignment)
            <a href="{{route('assignment.show', [$course, $assignment])}}">
                <x-card class="flex-auto flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $assignment->title }}</flux:heading>
                    <flux:text class="mt-2">{{ $assignment->description }}</flux:text>
                </x-card>
            </a>
        @endforeach
    </flux:container>
</section>
