<?php

use App\Models\Course;
use App\Models\Score;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{booted, layout, mount, state};

layout('components.layouts.course');

state(['course', 'score']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course, 'score' => $this->score]));
});

mount(function (Course $course, Score $score) {
    $this->course = $course;
    $this->score = $score;
    //$score->assignment()->ddRawSql();
});

?>

<flux:container>
    @if( !is_null($score->assignment))
        <a href="{{route('assignment.show', [$course->id, $score->assignment->id])}}">
            <x-card class="flex-auto flex m-6">
                <flux:heading class="flex items-center gap-2">{{ $score->assignment->title }}</flux:heading>
                <flux:text class="mt-2">{{ $score->assignment->description }}</flux:text>
            </x-card>
        </a>
    @endif
    <flux:heading class="flex items-center gap-2">{{ $this->score->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->score->description }}</flux:text>
    <flux:text class="mt-2">Max Point: {{ $this->score->max_point }}</flux:text>
</flux:container>
