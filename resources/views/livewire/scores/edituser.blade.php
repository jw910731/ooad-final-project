<?php

use App\Models\Course;
use App\Models\Score;
use App\Models\User;
use App\Models\UserScore;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;
    public Score $score;
    public User $user;

    #[Validate('nullable|integer|min:0')]
    public int|null $score_point = null;

    public function mount(Course $course, Score $score, User $user): void
    {
        $this->course = $course;
        $this->score = $score;
        $this->user = $user;

        $userScore = UserScore::where('score_id', $score->id)
            ->where('user_id', $user->id)
            ->first();
        if(!is_null($userScore)){
            $this->score_point = $userScore->score_point;
        }
    }

    public function update(): void
    {
        $validated = $this->validate();

        UserScore::updateOrCreate(
            [
                'score_id' => $this->score->id,
                'user_id' => $this->user->id,
            ],
            [
                'score_point' => $validated['score_point'],
            ]
        );

        session()->flash('success', 'Score updated successfully.');

        $this->redirectRoute('score.show', [$this->course->id, $this->score->id]);
    }

    public function rendering($view): void
    {
        $view->layoutData(['course' => $this->course, 'score' => $this->score, 'user' => $this->user]);
    }
}

?>
<flux:container>
    <flux:heading class="mt-4">Edit Score for {{ $user->name }}</flux:heading>

    <x-card class="p-6">
        <form wire:submit="update">
            <flux:input
                type="number"
                label="Score"
                min="0"
                max="{{ $score->max_point }}"
                wire:model="score_point"
            />

            @error('score_point')
                <div class="text-red-600 mt-2">{{ $message }}</div>
            @enderror

            <div class="mt-6">
                <flux:button type="submit" variant="primary">Update Score</flux:button>
            </div>
        </form>
    </x-card>
</flux:container>
