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
    @can('update', $course)
        <flux:button :href="route('member.add',[$course->id])">Add</flux:button>
    @endcan
    <flux:container class="flex">
        @foreach( $course->users as $member)
            <a href="{{route('member.show', [$course, $member])}}">
                <x-card class="flex-auto flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $member->name }}</flux:heading>
                    @if(( $memberRole = $member->pivot->role) == 'teacher')
                        <flux:text class="mt-2">Teacher</flux:text>
                    @elseif( $memberRole == 'teaching_assistant')
                        <flux:text class="mt-2">Teaching Assistant</flux:text>
                    @elseif( $memberRole == 'student')
                        <flux:text class="mt-2">Student</flux:text>
                    @else
                        <flux:text class="mt-2">Helper</flux:text>
                    @endif
                </x-card>
            </a>
        @endforeach
    </flux:container>
</section>
