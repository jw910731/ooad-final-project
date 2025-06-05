<?php

use App\Models\Course;
use App\Models\Score;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;


new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;

    public function mount(Course $course)
    {
        $this->course = $course;
        //dd($course->scores()->get());
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function deleteScore(Score $target): void
    {
        if (!is_null($target)) {
            $target->userScores()->delete();
            $target->delete();
        }
    }
}

?>
<flux:container class="w-full">
    @foreach($course->scores()->get() as $score)
        <a href="{{route('score.show', [$course, $score])}}">
            <x-card class="flex-auto">
                <div class="flex mx-4">
                    <div class="flex-1">
                        <flux:heading class="flex-1 mt-2 items-center gap-2">{{$score->title}}</flux:heading>
                        <flux:text class="flex my-2">{!! nl2br(e($score->description)) !!}</flux:text>
                    </div>
                    @can('update', $this->course)
                        <flux:button variant="danger" class="flex mr-2"
                                     wire:confirm="Are you sure you want to remove this score from this course?"
                                     wire:click="deleteScore({{$score}})"
                        >
                            <x-icon name="trash"/>
                        </flux:button>
                    @endcan
                </div>
            </x-card>
        </a>
    @endforeach
    @can('update', $course)
        <flux:button :href="route('score.create',[$course->id])">Add</flux:button>
    @endcan
</flux:container>
