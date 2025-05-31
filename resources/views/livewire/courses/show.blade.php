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
    @can('update', $course)
        <flux:button :href="route('courses.add_info', $course)">Add</flux:button>
    @endcan
    <flux:heading class="flex items-center gap-2">{{ $this->course->title }}</flux:heading>
    <flux:text class="mt-2">{{ $this->course->description }}</flux:text>
    @foreach($course->infos()->orderBy('order')->get() as $info)
        <x-card class="flex-auto flex m-6">
            <flux:heading class="flex items-center gap-2">{{$info->title}}</flux:heading>
            <flux:text class="mt-2">{{ $info->description }}</flux:text>
            @foreach($info->files as $file)
                <a href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">{{$file->name}}</a>
            @endforeach
        </x-card>
    @endforeach
</flux:container>
