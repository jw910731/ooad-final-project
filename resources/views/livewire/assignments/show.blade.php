<?php

use App\Models\Assignment;
use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{state, booted, mount, layout};

layout('components.layouts.course');

state(['course', 'assignment']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

mount(function (Course $course, Assignment $assignment) {
    $this->course = $course;
    $this->assignments = $assignment;
});

?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->assignment->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->assignment->description }}</flux:text>
</flux:container>
