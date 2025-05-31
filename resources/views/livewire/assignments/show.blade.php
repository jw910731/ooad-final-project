<?php

use App\Models\Assignment;
use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{state, booted, mount, layout};

layout('components.layouts.course');

state(['course', 'assignment']);

/*
booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});*/

mount(function (Course $course, Assignment $assignment) {
    $this->course = $course;
    $this->assignments = $assignment;

    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

$Delete_assignment = function () {
    $assignment = Assignment::findOrFail($this->assignment->id);

    if ($assignment->course_id === $this->course->id) {
        $assignment->delete();
    }

    $this->redirectRoute('assignment.index', [$this->course]);
};

?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->assignment->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->assignment->description }}</flux:text>
    @if(auth()->user()->system_admin)
    <flux:button :href="route('assignment.edit',[$course, $assignment])">
        Edit assignment</flux:button>
    <flux:button
        wire:click.prevent="Delete_assignment"
        variant="danger"
        onclick="if (!confirm('Are you sure you want to remove this assignment from the course?')) return false;"
    >
    Remove assignment
    </flux:button>
        
    @endif
</flux:container>
