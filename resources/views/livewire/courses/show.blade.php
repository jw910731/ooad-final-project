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
});

?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->course->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->course->description }}</flux:text>
</flux:container>
