<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Info;
use App\Models\User;
use App\Models\FileSet;
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
    public $title = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate([
        'attachments' => 'array',
        'attachments.*' => 'max:128000000' // 128MB
    ])]
    public $attachments = [];

    public Course $course;

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function save(): void
    {
        $validated = $this->validate();
        $fileset_uuid = uuid_create();
        $info = $this->course->infos()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => 1,
            'file_set_id' => $fileset_uuid,
        ]);
        foreach ($validated['attachments'] as $attachment) {
            $path = $attachment->store('attachment');
            $name = $fileset_uuid . '-' . $attachment->getClientOriginalName();
            $file = $info->files()->create([
                'name' => $name,
                'path' => $path,
            ]);
        }
        $this->redirectRoute('courses.show', $this->course);
    }
}
?>

<flux:container>
    <form wire:submit="save">
        <flux:input class="mb-6" wire:model="title" :label="__('Info Title')" required
                    autofocus/>
        <flux:textarea class="mb-6" wire:model="description" :label="__('Info Description')"/>
        <flux:input type="file" wire:model="attachments" :label="__('Attachments')" multiple/>
        @error('attachments.*') <span class="error">{{ $message }}</span> @enderror
        <div class="mb-6">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
        </div>
    </form>
</flux:container>
