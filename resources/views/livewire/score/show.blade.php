<?php

use App\Models\Course;
use App\Models\Score;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{booted, layout, mount, state};

layout('components.layouts.course');

state(['course', 'score']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

mount(function (Course $course, Score $score) {
    $this->course = $course;
    $this->score = $score;
});

?>

<flux:container>
    <a href="{{route('assignment.show', [$course, $score/* ->assignment */])}}">
        "todo: a button route to current related assignment"
        <!-- todo: a button route to current related assignment-->
    </a>
    <flux:heading class="flex items-center gap-2">{{ $this->score->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->score->description }}</flux:text>
    <flux:text class="mt-2">Max Point: {{ $this->score->max_point }}</flux:text>
</flux:container>
