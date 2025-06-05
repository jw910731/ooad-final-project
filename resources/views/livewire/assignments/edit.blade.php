<?php

use App\Models\Assignment;
use App\Models\Course;
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


    #[Validate([
        'attachments' => 'array',
        'attachments.*' => 'max:128000000' // 128MB
    ])]
    public $attachments = [];

    public Course $course;
    public Assignment $assignment;


    public function mount(Course $course, Assignment $assignment): void
    {
        $this->course = $course;
        $this->assignment = $assignment;
        $this->title = $assignment->title;
        $this->description = $assignment->description;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function save(): void
    {
        $validated = $this->validate();
        $fileset_uuid = $this->assignment->file_set_id;
        if ( is_null($fileset_uuid)) {
            $fileset_uuid = uuid_create();
        }
        $this->assignment->update(
            ['title' => $validated['title'],
             'description' => $validated['description'],
             'file_set_id' => $fileset_uuid,]
        );
        foreach ($validated['attachments'] as $attachment) {
            $path = $attachment->store('attachment');
            $name = $attachment->getClientOriginalName();
            $file = $this->assignment->files()->create([
                'name' => $name,
                'path' => $path,
            ]);
        }
        $this->redirectRoute('assignment.show', [$this->course, $this->assignment]);
    }

    public function deleteAppendix(File $target): void
    {
        $target->delete();
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
            <form wire:submit="save">
                <flux:input class="mb-6" wire:model="title" :label="__('Assignment Title')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Assignment Description')"/>
                <flux:heading class="flex mt-6">
                    Appendix
                </flux:heading>
                @foreach($this->assignment->files as $file)
                    <x-card class="flex m-6">
                        <div class="flex -my-4 ">
                            <flux:text class="flex-1 mt-2">
                                <a href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                                    {{$file->name}}
                                </a>
                            </flux:text>
                            <flux:button icon="trash"
                                         variant="danger"
                                         wire:confirm="Are you sure you want to remove this appendix from this assignment?"
                                         wire:click="deleteAppendix({{$file}})"
                            >
                            </flux:button>
                        </div>
                    </x-card>
                @endforeach
                <div class="mt-4 mb-4">
                    <flux:input type="file" wire:model="attachments" multiple/>
                    @error('attachments.*') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
