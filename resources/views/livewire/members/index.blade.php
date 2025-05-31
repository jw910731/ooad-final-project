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
    //$this->member = $member;
    $this->course = $course;
    //dd();
});

?>

<section class="w-full">
    <flux:container class="flex">
        @foreach( $course->users()->get() as $member)
            <a href="{{route('member.show', [$course, $member])}}">
                <x-card class="flex-auto flex m-6">
                    <flux:heading class="flex items-center gap-2">{{ $member->name }}</flux:heading>
{{--                    <flux:text class="mt-2">{{ }}</flux:text>--}}
                </x-card>
            </a>
        @endforeach
    </flux:container>
</section>
