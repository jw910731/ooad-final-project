<?php

use App\Models\Course;
use App\Models\Score;
use App\Models\UserScore;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{booted, layout, mount, state};

layout('components.layouts.course');

state(['course', 'score', 'userScores']);

booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course, 'score' => $this->score]));
});

mount(function (Course $course, Score $score) {
    $this->course = $course;
    $this->score = $score;
    $this->userScores = UserScore::with('user')
        ->where('score_id', $score->id)
        ->get();
});

?>

<flux:container>
    @if( !is_null($score->assignment))
        <a href="{{route('assignment.show', [$course->id, $score->assignment->id])}}">
            <x-card class="flex-auto flex m-6">
                <flux:heading class="flex items-center gap-2">{{ $score->assignment->title }}</flux:heading>
                <flux:text class="mt-2">{!! nl2br(e($score->assignment->description))  !!}</flux:text>
            </x-card>
        </a>
    @endif

    <flux:heading class="flex items-center gap-2">{{ $this->score->title }}</flux:heading>
    <flux:text class="mt-2">{!!  nl2br(e($this->score->description)) !!}</flux:text>
    <flux:text class="mt-2">Max Point: {{ $this->score->max_point }}</flux:text>

    @can('update', $course)
        <flux:button :href="route('score.adduser',[$course, $score])">Add points to students</flux:button>
        <flux:button :href="route('score.edit', [$course->id, $score->id])">Edit score rule</flux:button>
    @endcan

    <table class="mt-4 w-full">
        <thead>
            <tr>
                <th class="text-left">Student Name</th>
                <th class="text-left">Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->userScores as $userScore)
                <tr @click="window.location='{{ route('score.edituser', [$course->id, $score->id, $userScore->user->id]) }}'" class="cursor-pointer hover:bg-gray-100">
                    <td>{{ $userScore->user->name }}</td>
                    <td>{{ $userScore->score_point }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</flux:container>

