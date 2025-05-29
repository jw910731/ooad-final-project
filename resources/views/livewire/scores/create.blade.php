<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;

//use function Livewire\Volt\{booted, layout, mount, state};

//layout('components.layouts.course');

//state(['course']);
/*
booted(function () {
    $this->attributes->add(new Layout('components.layouts.course', ['course' => $this->course]));
});*/

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;
    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|numeric')]
    public ?int $max_point = null;

    #[Validate('required')]
    public ?int $assignment_id = null;

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function create(): void
    {
        $course = Course::find($this->course->id);
        $score = $course->scores()->create([
            'title' => $this->title,
            'description' => $this->description,
            'course_id' => $this->course->id,
            'max_point' => $this->max_point,
            'order' => 1,//i dont know how order effect us
        ]);
        //dd($score);
        $assignment = Assignment::find($this->assignment_id);
        $assignment->update([
            'score_id' => $score->id,
        ]);
        $this->redirectRoute('score.index', [$course]);
    }

}

?>
<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Create Scores') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="create">
                <x-select
                        label="Assignment of the Score"
                        placeholder="Select assignment in this course"
                        :async-data="[
                            'api' => route('assignmentSearch.search'),
                            'params' => ['course_id' => $course->id],
                            'credentials' => 'include',
                        ]"
                        option-label="title"
                        option-value="id"
                        wire:model="assignment_id"/>
                <flux:input class="mb-6" wire:model="title" :label="__('Score Title')" required
                            autofocus/>
                <flux:input class="mb-6" wire:model="max_point" :label="__('Max Point')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Score Description')"/>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
