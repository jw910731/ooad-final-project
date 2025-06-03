<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;

new class extends Component
{
    // public Course $course;
    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required')]
    public ?int $user_id = null;

    public Course $course;
    public User $member;

    public function mount(Course $course, User $member)
    {
        $this->course = $course;
        $this->member = $member;
    }

    public $role;


    public function Member_change(): void
    {
        $validated = $this->validate([
            'role' => 'required|string|in:teacher,teaching_assistant,student,helper',
        ]);
        $course = Course::find($this->course->id);

        $userId = $this->member;

        $this->course->users()->updateExistingPivot($userId, [
            'role' => $validated['role'],
        ]);

        $this->redirectRoute('member.index', [$this->course]);
    }
}
?>
<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Set roles') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="Member_change">
            <x-select
                label="Change Role"
                placeholder="Select new role"
                :options="[
                    ['name' => 'Teacher', 'role' => 'teacher'],
                    ['name' => 'Teaching Assistant', 'role' => 'teaching_assistant'],
                    ['name' => 'Student', 'role' => 'student'],
                    ['name' => 'Helper', 'role' => 'helper'],
                ]"
                option-label="name"
                option-value="role"
                wire:model="role"/>
            <div class="mb-6">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('Change') }}</flux:button>
            </div>
        </form>
        </x-card>
    </div>
</flux:container>
