<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.course')]
class extends Component {
    public Course $course;

    #[Validate([
        'user_id' => 'required',
        'user_id.*' => 'exists:users,id',
    ])]
    public $user_id = [];

    #[Validate('required|string')]
    public $role = null;

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function addMember(): void
    {
        $validated = $this->validate();
        //dd($this->role);
        $course = Course::find($this->course->id);
        $course->users()->attach(
            array_map(function($value) use (&$validated) {
                return ['role' => $validated['role']];
            }, array_flip($validated['user_id']))
        );
        $this->redirectRoute('member.index', [$course]);
    }

}
?>

<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Select Users') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="addMember">
                <x-select
                          label="Role of this course"
                          placeholder="Select role for new members"
                          :options="[
                            ['name' => 'Teacher', 'role' => 'teacher'],
                            ['name' => 'Teaching Assistant', 'role' => 'teaching_assistant'],
                            ['name' => 'Student', 'role' => 'student'],
                            ['name' => 'Helper', 'role' => 'helper'],
                          ]"
                          option-label="name"
                          option-value="role"
                          wire:model="role"/>
                <x-select multiselect
                    label="New members of this course"
                    placeholder="Select users as new member"
                    :async-data="[
                        'api' => route('userSearch.search'),
                        'params' => ['excludeCourse_id' => $course->id],
                        'credentials' => 'include',
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="user_id"/>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Add member') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
