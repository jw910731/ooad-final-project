<?php

use App\Models\Course;
use App\Models\Score;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;
    public Score $score;

    #[Validate('required|array')]
    public array $user_ids = [];

    #[Validate('required|array')]
    public array $user_scores = []; // [user_id => score_point]

    public function mount(Course $course, Score $score): void
    {
        $this->course = $course;
        $this->score = $score;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function create(): void
    {
        $rules = [
            'user_ids' => 'required|array',
            'user_scores' => 'required|array',
        ];

        foreach ($this->user_scores as $user_id => $score_point) {
            $rules["user_scores.$user_id"] = 'required|integer|min:0|max:' . $this->score->max_point;
        }

        $validated = $this->validate($rules);
        $score = Score::find($this->score->id);
        $duplicateUsers = [];

        foreach ($this->user_scores as $user_id => $score_point) {
            $exists = $score->userScores()
                ->where('user_id', $user_id)
                ->exists();

            if ($exists) {
                $duplicateUsers[] = $user_id;
            } else {
                $score->userScores()->create([
                    'user_id' => $user_id,
                    'score_point' => $score_point,
                ]);
            }
        }

        if (!empty($duplicateUsers)) {
            $userNames = \App\Models\User::whereIn('id', $duplicateUsers)->pluck('name')->toArray();

            // generate error message
            $this->addError('user_scores', 'Scores for the following users already exist: ' . implode(', ', $userNames));

            return;
        }

        // redirect if there is no problem
        $this->redirectRoute('score.index', [$this->course]);
    }
}

?>
<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Add User Scores') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="create">
                <x-select multiselect
                    label="User of this course"
                    placeholder="Select users to add scores"
                    :async-data="[
                        'api' => route('userSearch.search'),
                        'params' => ['includeCourse' => $course->id, 'requireRole' => 'student'],
                        'credentials' => 'include',
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="user_ids"
                />

                @foreach ($user_ids as $user_id)
                    <flux:input
                        class="mt-4"
                        label="Score for User ID: {{ $user_id }}"
                        type="number"
                        min="0"
                        max="{{ $score->max_point }}"
                        wire:model.defer="user_scores.{{ $user_id }}"
                        required
                    />
                @endforeach

                {{-- show errors --}}
                @error('user_scores')
                    <div class="text-red-600 mt-2">{{ $message }}</div>
                @enderror

                <div class="mt-6">
                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Submit Scores') }}
                    </flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>