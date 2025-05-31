<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;

new class extends Component {
    //public Course $course;
    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required')]
    public ?int $user_id = null;

    public Course $course;
        public function mount(Course $course) {
          $this->course = $course;
    }

    public function Member_change(): void
    {
        $this->course->assignments()->create([
            'course_id' => $this->course->id,
            'order' => 1,
            'title' => $this->title,
            'description' => $this->description
        ]);
        $this->redirectRoute('member.index', $this->course);
    }
    public function Delete_member(): void
    {
        $this->course->assignments()->create([
            'course_id' => $this->course->id,
            'order' => 1,
            'title' => $this->title,
            'description' => $this->description
        ]);
        $this->redirectRoute('member.index', $this->course);
    }
}

?>
<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Create Assignments') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="Member_change">
                <x-select
                    label="Teacher of the Assignment"
                    placeholder="Select user as teacher"
                    :async-data="[
                        'api' => route('userSearchTeacher.search'),
                        'params' => ['excludeStudent' => $course->id],
                        'credentials' => 'include',
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="user_id"/>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Change') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
