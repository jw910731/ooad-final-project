<?php

use App\Models\Course;
use App\Models\Assignment;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;
use function Livewire\Volt\{state};

state(['courses' => fn() => auth()->user()->courses()->get()]);

$delete_course = function ( Course $target): void
{
    //dd($target);
    if( is_null($target)){
        return;
    }

    $assignments = $target->assignments;
    $scores = $target->scores;
    $infos = $target->infos;

    if(!is_null($scores)){
        $scores->userScores()->delete();
        $scores->delete();
    }

    if(!is_null($assignments)){
        $userAssignments = $assignments->userAssignment;
        if(!is_null($userAssignments)){
            foreach ( $userAssignments as $userAssignment){
                $userAssignment->files->delete();
                $userAssignment->delete();
            }
        }
        foreach ( $assignments as $assignment){
            $assignment->files->delete();
            $assignment->delete();
        }
    }

    if(!is_null($infos)){
        foreach ( $infos as $info){
            $info->files->delete();
            $info->delete();
        }
    }

    $target->users()->detach();
    $target->delete();
}
?>

<section class="w-full">
    <flux:heading class="flex-1 items-center gap-2" style="font-size:14pt">
        Courses
    </flux:heading>
    <div class="px-0">
        @foreach($courses as $course)
            <a href="{{route('courses.show', $course)}}" class="m-0">
                <x-card class="flex my-6">
                    <div class="ml-4">
                        <div class="flex">
                            <flux:heading class="flex-1 items-center gap-2" style="font-size:14pt">
                                {{ $course->title }}
                            </flux:heading>
                            @can('delete', $course)
                                <flux:button
                                    variant="danger"
                                    wire:click.prevent="delete_course({{$course}})"
                                >
                                    <x-icon name="trash"></x-icon>
                                </flux:button>
                            @endcan
                        </div>
                        <flux:text class="mx-6">{!! nl2br(e($course->description)) !!}</flux:text>
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>
    @can('create', App\Models\Course::class)
        <flux:button :href="route('courses.create')">Add</flux:button>
    @endcan
</section>
