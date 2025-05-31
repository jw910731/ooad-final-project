<?php

use App\Models\User;
use App\Models\Course;
use Livewire\Attributes\Layout;
use function Livewire\Volt\{state, booted, mount, layout};

layout('components.layouts.course');

state(['course', 'member']);

mount(function (Course $course, User $member) {
    $this->course = $course;
    $this->member = $member;

    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});

$Delete_member = function () {
    $course = Course::find($this->course->id);
    $user = $this->member;

    $course->users()->detach($user->id);

    $this->redirectRoute('member.index', [$this->course]);
};


?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->member->name }}</flux:heading>
    @if(auth()->user()->system_admin)
        <flux:button :href="route('member.update',[$course, $member])">
        Change member role</flux:button>
        <flux:button
            wire:click.prevent="Delete_member"
            variant="danger"
            onclick="if (!confirm('Are you sure you want to remove this member from the course?')) return false;"
        >
        Remove Member
        </flux:button>
        
    @endif

    <flux:text class="mt-2">{{ $this->member->description }}</flux:text>
</flux:container>
