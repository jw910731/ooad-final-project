<?php

use App\Models\Course;
use App\Models\Info;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.course')]
class extends component {
    public Course $course;

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function rendering(View $view): void
    {
        $view->layoutData(['course' => $this->course]);
    }

    public function deleteInfo(Info $info): void
    {
        if(!is_null($info)){
            $info->files()->delete();
            $info->delete();
        }
    }
}

?>

<flux:container>
    <x-card>
        <flux:heading class="flex items-center gap-2" style="font-size:16pt">{{ $this->course->title }}</flux:heading>
        <flux:text class="mt-2 mx-6">{!! nl2br(e($this->course->description)) !!}</flux:text>
    </x-card>
    <flux:heading class="flex mt-2  items-center gap-2">Information</flux:heading>
    <div>
        @foreach($course->infos()->orderBy('order')->get() as $info)
            <x-card class="flex mx-0">
                <div class="flex mx-0">
                    <flux:heading class="flex-1 mt-2 items-center gap-2">{{$info->title}}</flux:heading>
                    <flux:button  class="flex mr-2"
                                 :href="route('courses.edit_info',[$this->course, $info])">
                        edit
                    </flux:button>
                    <flux:button variant="danger" class="flex"
                                 wire:confirm="Are you sure you want to remove this info from this course?"
                                 wire:click="deleteInfo({{$info}})">
                        <x-icon name="trash"/>
                    </flux:button>
                </div>
                <flux:text class="flex my-2 mx-6">{!! nl2br(e($info->description)) !!}</flux:text>
                @foreach($info->files as $file)
                    <x-card>
                        <flux:text class="mt-2">
                            <x-icon name="document" class="inline w-5 h-5"/>
                            <a href="{{Storage::temporaryUrl($file->path, now()->addMinute(5))}}">
                                {{$file->name}}
                            </a>
                        </flux:text>
                    </x-card>
                @endforeach
            </x-card>
        @endforeach
        @can('update', $course)
            <flux:button class="flex mt-2" :href="route('courses.add_info', $course)">Add</flux:button>
        @endcan
    </div>

</flux:container>
