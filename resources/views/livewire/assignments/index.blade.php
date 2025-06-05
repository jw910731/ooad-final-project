<?php

//use function Livewire\Volt\{state};

use App\Models\Assignment;
use App\Models\Course;
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
        if(!is_null($target)){
            $target->userAssignment()->delete();
            $target->delete();
        }
    }
}

?>

<section class="w-full">
    <div>
        @foreach($course->assignments as $assignment)
            <a href="{{route('assignment.show', [$course, $assignment])}}">
                <x-card class="flex-auto flex m-6">
                    <div class="flex mx-4">
                        <div class="flex-1 mx-0">
                            <flux:heading class="flex-1 mt-2 items-center gap-2">{{$assignment->title}}</flux:heading>
                            <flux:text class="flex my-2 mx-6">{!! nl2br(e($assignment->description)) !!}</flux:text>
                        </div>
                        @can('update', $this->course)
                            <flux:button variant="danger" class="flex mr-2"
                                         wire:confirm="Are you sure you want to remove this info from this course?"
                                         wire:click="deleteAssignment({{$assignment}})">
                                <x-icon name="trash"/>
                            </flux:button>
                        @endcan
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>
    @can('update', $course)
        <flux:button :href="route('assignment.create',$course)">Add</flux:button>
    @endcan
</section>
