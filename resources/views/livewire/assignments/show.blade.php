<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\File;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\View\View;
use Livewire\WithFileUploads;


new #[Layout('components.layouts.course')]
class extends Component {
    use WithFileUploads;

    public Course $course;
    public Assignment $assignment;
    #[Validate([
        'attachments' => 'array',
        'attachments.*' => 'max:128000000' // 128MB
    ])]
    public $attachments = [];

    public function mount(Course $course, Assignment $assignment): void
    {
        $this->course = $course;
        $this->assignment = $assignment;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course, 'assignment' => $this->assignment]);
    }

    public function deleteAssignment(): void
    {
        $assignment = Assignment::findOrFail($this->assignment->id);

        if ($assignment->course_id === $this->course->id) {
            $assignment->delete();
        }

        $this->redirectRoute('assignment.index', [$this->course]);
    }

    public function saveSubmissions(): void
    {
        $validated = $this->validate();
        $fileset_uuid = uuid_create();
        $userAssignment = $this->assignment->userAssignment()->updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['file_set_id' => $fileset_uuid]
        );
        foreach ($validated['attachments'] as $attachment) {
            $path = $attachment->store('attachment');
            $name = $fileset_uuid . '-' . $attachment->getClientOriginalName();
            $file = $userAssignment->files()->create([
                'name' => $name,
                'path' => $path,
            ]);
        }
        $this->redirectRoute('assignment.show', [$this->course, $this->assignment]);
    }
    public function delete_hand_on( File $target): void
    {
        $target->delete();
        $this->redirectRoute('assignment.show', [$this->course, $this->assignment]);
    }
}

?>

<flux:container>
    <x-card >
        <flux:heading class="flex items-center gap-2">{{ $this->assignment->title }}</flux:heading>
        <flux:text class="mt-2">{{ $this->assignment->description }}</flux:text>
    </x-card>
    @can('update', $this->assignment)
        <flux:button :href="route('assignment.edit',[$course, $assignment])">
            Edit assignment
        </flux:button>
    @endcan
    @can('delete', $this->assignment)
        <flux:button
            wire:click.prevent="deleteAssignment"
            variant="danger"
            onclick="if (!confirm('Are you sure you want to remove this assignment from the course?')) return false;"
        >
            Remove assignment
        </flux:button>
    @endcan
    @can('hand_on', $this->assignment)
        <flux:heading class="flex mt-6">Submissions</flux:heading>
        <b class="flex mt-0">
            @foreach($this->assignment->userAssignment()->where('user_id', auth()->user()->id)->first()->files as $file)
                <a href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                    <x-card class="flex m-6 flex m-6">
                        <flux:text class="mt-1">{{$file->name}}</flux:text>
                        <flux:button icon="trash"
                                     variant="danger"
                                     onclick="if (!confirm('Are you sure you want to remove this submission from this assignment?')) return false;"
                                     wire:click.prevent="delete_hand_on({{$file}})"
                        >
                            Remove submission
                        </flux:button>
                    </x-card>
                </a>
            @endforeach
        </b>
        <form wire:submit="saveSubmissions">
            <flux:input type="file" wire:model="attachments" :label="__('Submits')" multiple/>
            @error('attachments.*') <span class="error">{{ $message }}</span> @enderror
            <div class="mb-6">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('submit') }}</flux:button>
            </div>
        </form>
    @endcan

</flux:container>
