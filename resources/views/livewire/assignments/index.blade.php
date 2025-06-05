<?php

//use function Livewire\Volt\{state};

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Score;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function deleteAssignment( Assignment $target): void
    {
        //dd($target);
        if(is_null($target)){
            return;
        }
        if(!is_null($score = $target->score)){
            //dd($scoreTarget);
            $score->userScores()->delete();
            $score->delete();
        }
        $target->userAssignment()->('files')->delete();
        /*
        if(!is_null($userAssignments = $target->userAssignment)){
            foreach ( $userAssignments as $userAssignment){
                $userAssignment->files->delete();
                $userAssignment->delete();
            }
        }*/
        $target->files()->delete();
        $target->delete();
    }
}

?>

<flux:container class="w-full">
    @foreach($course->assignments as $assignment)
        <a href="{{route('assignment.show', [$course, $assignment])}}">
            <x-card class="flex-auto">
                <div class="flex mx-4">
                    <div class="flex-1">
                        <flux:heading class="flex-1 mt-2 items-center gap-2">{{$assignment->title}}</flux:heading>
                        <flux:text class="flex my-2">{!! nl2br(e($assignment->description)) !!}</flux:text>
                    </div>
                    @can('update', $this->course)
                        <flux:button variant="danger" class="flex mr-2"
                                     wire:confirm="Are you sure you want to remove this assignment from this course?"
                                     wire:click.prevent="deleteAssignment({{$assignment}})">
                            <x-icon name="trash"/>
                        </flux:button>
                    @endcan
                </div>
            </x-card>
        </a>
    @endforeach
    @can('update', $course)
        <flux:button :href="route('assignment.create',$course)">Add</flux:button>
    @endcan
</flux:container>
