<?php

use App\Models\User;
use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{state, booted, mount, layout};

layout('components.layouts.course');

state(['course', 'member']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

mount(function (Course $course, User $member) {
    $this->course = $course;
    $this->member = $member;
    $name = $member->name;
});

?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->member->name }}</flux:heading>
    @if(auth()->user()->system_admin)
        <flux:button :href="route('assignment.create',$course)">
        Change member role</flux:button>
    @endif
    <flux:text class="mt-2">{{ $this->member->description }}</flux:text>
</flux:container>
