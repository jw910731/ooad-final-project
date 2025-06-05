<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Info;
use App\Models\User;
use App\Models\File;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.course')]
class extends Component {
    use WithFileUploads;

    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    public Course $course;


    public function mount(Course $course): void
    {
        $this->course = $course;
        $this->title = $info->title;
        $this->description = $info->description;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }
    public function save(): void
    {
        $validated = $this->validate();
        $fileset_uuid = null;
        $this->course->update(
            ['title' => $validated['title'],
                'description' => $validated['description'],]
        );
        $this->redirectRoute('courses.show', [$this->course]);
    }
}
?>


<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Edit Information') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="save">
                <flux:input class="mb-6" wire:model="title" :label="__('Information Title')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Information Description')"/>
                <flux:heading class="flex mt-6">
                    Appendix
                </flux:heading>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>

