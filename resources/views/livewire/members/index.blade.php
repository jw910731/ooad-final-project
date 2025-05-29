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
    //dd();
});

?>

<section class="w-full">
    @if(auth()->user()->courses()->find($course->id)->pivot->role == 'teacher')
        <flux:button :href="route('member.add',[$course->id])">Add</flux:button>
    @endif
    <flux:container class="flex">
        @foreach( $course->users as $member)
            <a href="{/*{route('courses.show', $course)}*/}">
                <x-card class="flex-auto flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $member->name }}</flux:heading>
                    <flux:text class="mt-2">{{ $member->pivot->role}}</flux:text>
                </x-card>
            </a>
        @endforeach
    </flux:container>
</section>
