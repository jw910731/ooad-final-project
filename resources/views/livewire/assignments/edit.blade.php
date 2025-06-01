<?php

use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required')]
    public ?int $user_id = null;

    public Course $course;
    public Assignment $assignment;


    public function mount(Course $course, Assignment $assignment) {
        $this->course = $course;
        $this->assignment = $assignment;
        $this->title = $assignment->title;
        $this->description = $assignment->description;
    }

    public function Assignment_edit(): void
    {
        $this->assignment->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->redirectRoute('assignment.index', $this->course);
    }

    // App\Models\Course.php
    public function assignments()
    {
        return $this->belongsToMany(Assignment::class)->withPivot('title', 'description');
    }
}

?>
<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Edit Assignments') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="Assignment_edit">
                <flux:input class="mb-6" wire:model="title" :label="__('Assignment Title')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Assignment Description')"/>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Edit') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
