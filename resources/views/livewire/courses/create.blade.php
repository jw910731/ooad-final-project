<?php

use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string')]
    public $title = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate([
        'userTeacher_id' => 'required',
        'userTeacher_id.*' => 'exists:users,id',
    ])]
    public $userTeacher_id = [];

    #[Validate([
        'userTA_id.*' => 'exists:users,id',
    ])]
    public $userTA_id = [];
    #[Validate([
        'userStudent_id.*' => 'exists:users,id',
    ])]
    public $userStudent_id = [];

    public function create(): void
    {
        $validated = $this->validate();
        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);
        $course->users()->attach(
            array_map(function($value) use (&$validated) {
                return ['role' => 'teacher'];
            }, array_flip($validated['userTeacher_id']))
        );
        if( $this->userTA_id != []){
            $course->users()->attach(
                array_map(function ($value) use (&$validated) {
                    return ['role' => 'teaching_assistant'];
                }, array_flip($validated['userTA_id']))
            );
        }
        if($this->userStudent_id != []){
            $course->users()->attach(
                array_map(function ($value) use (&$validated) {
                    return ['role' => 'student'];
                }, array_flip($validated['userStudent_id']))
            );
        }
        $this->redirectRoute('courses.index');
    }
}

?>



<flux:container>
    <div class="flex w-full flex-col gap-2">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Create Courses') }}</flux:heading>
            <flux:separator variant="subtle"/>
        </div>
        <x-card class="p-6">
            <form wire:submit="create">
                <flux:input class="mb-6" wire:model="title" :label="__('Course Title')" required
                            autofocus/>
                <flux:textarea class="mb-6" wire:model="description" :label="__('Course Description')"/>
                <x-select multiselect
                          label="Teacher of the Course"
                          placeholder="Select user as teacher"
                          :async-data="[
                            'api' => route('userSearch.search'),
                            'credentials' => 'include',
                            ]"
                          option-label="name"
                          option-value="id"
                          wire:model="userTeacher_id"/>
                <x-select multiselect
                          label="Teaching assistant of the Course"
                          placeholder="Select users as teaching assistant"
                          :async-data="[
                                'api' => route('userSearch.search'),
                                'credentials' => 'include',
                            ]"
                          option-label="name"
                          option-value="id"
                          wire:model="userTA_id"/>
                <x-select multiselect
                          label="Students of the Course"
                          placeholder="Select users as students"
                          :async-data="[
                                'api' => route('userSearch.search'),
                                'credentials' => 'include',
                            ]"
                          option-label="name"
                          option-value="id"
                          wire:model="userStudent_id"/>

                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
