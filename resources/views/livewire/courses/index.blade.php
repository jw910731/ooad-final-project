<?php

use function Livewire\Volt\{state};

state(['courses' => fn () => auth()->user()->courses()->get()]);
?>

<section class="w-full">
    @if(auth()->user()->system_admin)
        <flux:button :href="route('courses.create')">Add</flux:button>
    @endif
    <flux:container class="flex">
        @foreach($courses as $course)
            <x-card class="flex-auto flex">
                <flux:heading class="flex items-center gap-2">{{ $course->title }}</flux:heading>
                <flux:text class="mt-2">{{ $course->description }}</flux:text>
            </x-card>
        @endforeach
    </flux:container>
</section>
