<?php

use App\Models\Assignment;
use App\Models\Course;
use App\Models\File;
use App\Models\UserAssignment;
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
    public UserAssignment|null $userAssignment= null;
    #[Validate([
        'attachments' => 'array',
        'attachments.*' => 'max:128000000' // 128MB
    ])]
    public $attachments = [];

    public function mount(Course $course, Assignment $assignment): void
    {
        $this->course = $course;
        $this->assignment = $assignment;
        $this->userAssignment = $this->assignment->userAssignment()->where('user_id', auth()->user()->id)->first();
        //dd($this->assignment->files()->get());
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course, 'assignment' => $this->assignment]);
    }

    public function saveSubmissions(): void
    {
        $validated = $this->validate();
        $fileset_uuid = null;
        if (!is_null($this->userAssignment)) {
            $fileset_uuid = $this->userAssignment->file_set_id;
        }
        if( is_null($fileset_uuid)){
            $fileset_uuid = uuid_create();
        }
        $userAssignment = $this->assignment->userAssignment()->updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['file_set_id' => $fileset_uuid]
        );
        foreach ($validated['attachments'] as $attachment) {
            $path = $attachment->store('attachment');
            $name = $attachment->getClientOriginalName();
            $file = $userAssignment->files()->create([
                'name' => $name,
                'path' => $path,
            ]);
        }
        $this->redirectRoute('assignment.show', [$this->course,$this->assignment]);
    }
    public function deleteSubmission(File $target): void
    {
        $target->delete();
    }
}

?>

<flux:container>
    <flux:button
        :href="route('assignment.index',[$course])"
    >
        <x-icon name="arrow-left"/>
        Assignment List
    </flux:button>
    <x-card>
        <div class="flex">
            <div class="flex-1">
                <flux:heading class="flex items-center gap-2">{{ $this->assignment->title }}</flux:heading>
                <flux:text class="mt-2 ml-6">{!! nl2br(e($this->assignment->description))  !!}</flux:text>
            </div>
            @can('update', $this->assignment)
                <flux:button :href="route('assignment.edit',[$course, $assignment])"
                             class="mr-2"
                >
                    edit
                </flux:button>
            @endcan
        </div>
        <flux:heading class="flex mt-2">
            Appendix
        </flux:heading>
        @if(!is_null(($temp = $this->assignment->files)->first()))
            <x-card  class="flex mt-1 ">
                @foreach($temp as $file)
                    <flux:text class="mt-1" >
                            <a class="inline cursor-pointer hover:bg-gray-200" href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                                <x-icon name="document" class="inline w-5 h-5"/>
                                {{$file->name}}
                            </a>
                    </flux:text>
                @endforeach
            </x-card>
        @else
            <flux:text class="flex mt-2 mx-6">
                There is no appendix of this assignment now
            </flux:text>
        @endif
    </x-card>
    <flux:heading class="flex mt-6">Submissions</flux:heading>
{{--    grader's view--}}
    @can('update', $this->assignment)
        @foreach( $this->course->users()->where('role','student')->get() as $user)
            <x-card class="flex m-6">
                <flux:heading class="flex">{{$user->name}}</flux:heading>
                @if((!is_null($user->userAssignment()))
                    && !is_null( $tempUserAssignment = $user->userAssignment()->where('assignment_id',$this->assignment->id)->first())
                    && !is_null($tempUserAssignment->files()->first()))
                        <x-card class="flex m-0 ">
                            @foreach($tempUserAssignment->files as $file)
                                <flux:text class="flex-1 mt-2">
                                    <a class="cursor-pointer hover:bg-gray-200"
                                       href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                                        {{$file->name}}
                                    </a>
                                </flux:text>
                            @endforeach
                        </x-card>
                @else
                    <x-card class="flex m-0">
                        <flux:text class="mt-2">
                            There is no submissions of {{$user->name}}
                        </flux:text>
                    </x-card>
                @endif
            </x-card>
        @endforeach
    @endcan
{{--    student's view--}}
    @can('submit', $this->assignment)
        @if((!is_null($this->userAssignment))&&($this->userAssignment->files != []))
            @foreach($this->userAssignment->files as $file)
                <x-card class="flex m-6">
                    <div class="flex -my-4 ">
                        <flux:text class="flex-1 mt-2">
                            <a href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                                {{$file->name}}
                            </a>
                        </flux:text>
                        <flux:button icon="trash"
                                     variant="danger"
                                     wire:confirm="Are you sure you want to remove this submission from this assignment?"
                                     wire:click="deleteSubmission({{$file}})"
                        >
                        </flux:button>
                    </div>
                </x-card>
            @endforeach
        @else
            <flux:text class="mt-2 ml-6">
                There is no submissions now
            </flux:text>
        @endif
        <form wire:submit="saveSubmissions" class="mt-6">
            <flux:input type="file" wire:model="attachments" :label="__('Submits')" multiple/>
            @error('attachments.*') <span class="error">{{ $message }}</span> @enderror
            <div class="mb-6">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('submit') }}</flux:button>
            </div>
        </form>
    @endcan

</flux:container>
