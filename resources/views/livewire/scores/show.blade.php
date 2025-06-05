<?php

use App\Models\Course;
use App\Models\Score;
use App\Models\UserScore;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;
    public Score $score;
    public $userScores;
    public function mount(Course $course, Score $score): void
    {
        $this->course = $course;
        $this->score = $score;
        $this->userScores = UserScore::with('user')
            ->where('score_id', $score->id)
            ->get();
        //dd($this->userScores);
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course, 'score' => $this->score]);
    }
}


?>

<flux:container>
    <flux:heading class="flex items-center gap-2">{{ $this->score->title }}</flux:heading>
    <flux:text class="mt-2 ml-6">{!!  nl2br(e($this->score->description)) !!}</flux:text>
    <flux:text class="mt-2">Max Point: {{ $this->score->max_point }}</flux:text>
    <x-card>
        <flux:heading class="flex items-center gap-2 mt-2">
            Related Assignment
        </flux:heading>
        @if( !is_null($score->assignment))
            <a href="{{route('assignment.show', [$course->id, $score->assignment->id])}}">
                <x-card class="flex-auto">
                    <flux:heading class="flex items-center gap-2">{{ $score->assignment->title }}</flux:heading>
                    <flux:text class="mt-2">{!! nl2br(e($score->assignment->description))  !!}</flux:text>
                </x-card>
            </a>
        @endif
    </x-card>

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
        @foreach($course->users()->where('role', 'student')->get() as $student)
            <tr @click="window.location='{{ route('score.edituser', [$course, $score, $student]) }}'"
                class="cursor-pointer hover:bg-gray-100">
                <td>
                    <flux:text>
                        {{ $student->name }}
                    </flux:text>
                </td>
                @if(!is_null($temp = $userScores->where('user_id', $student->id)->first()))
                    <td>
                        <flux:text>
                            {{ $userScores->where('user_id', $student->id)->first()->score_point }}
                        </flux:text>
                    </td>
                @else
                    <td>
                        <flux:text>No record</flux:text>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

</flux:container>

