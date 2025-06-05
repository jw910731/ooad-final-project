<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;
use Livewire\WithFileUploads;
//use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
new #[Layout('components.layouts.course')]
class extends Component {
    use WithFileUploads;

    public Course $course;

    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';


    #[Validate([
        'attachments' => 'array',
        'attachments.*' => 'max:128000000' // 128MB
    ])]
    public $attachments = [];

    public  $deadline;
    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function assignmentCreate(): void
    {
        $validated = $this->validate();
        $fileset_uuid = uuid_create();
        $assignment = $this->course->assignments()->create([
            'course_id' => $this->course->id,
            'order' => 1,
            'title' => $this->title,
            'description' => $this->description,
            'file_set_id' => $fileset_uuid,
            'deadline' => $this->deadline,
        ]);
        foreach ($validated['attachments'] as $attachment) {
            $path = $attachment->store('attachment');
            $name = $attachment->getClientOriginalName();
            $file = $assignment->files()->create([
                'name' => $name,
                'path' => $path,
            ]);
        }
        $this->redirectRoute('assignment.index', $this->course);
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
            <form wire:submit="assignmentCreate">
                <flux:input class="mb-6" wire:model="title" :label="__('Assignment Title')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Assignment Description')"/>

                <div class="mt-4 mb-4">
                    <flux:input type="file" wire:model="attachments" multiple/>
                    @error('attachments.*') <span class="error">{{ $message }}</span> @enderror
                    <div class="mx-6 mt-2">
                        @foreach( $attachments as $attachment)
                            <flux:text class="mt-2">
                                {{ $attachment->getClientOriginalName() }}
                            </flux:text>
                        @endforeach
                    </div>
                </div>
                <x-datetime-picker
                    label="Appointment Date"
                    placeholder="Appointment Date"
                    parse-format="YYYY-MM-DD HH:mm:ss"
                    wire:model.defer="deadline"
                />
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
