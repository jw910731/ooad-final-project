<?php

use App\Models\Course;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required')]
    public ?int $user_id = null;

    public function create(): void
    {
        $user = User::find($this->user_id);
        $user->courses()->create([
            'title' => $this->title,
            'description' => $this->description
        ], ['role' => 'teacher']);
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
                <x-select
                    label="Teacher of the Course"
                    placeholder="Select user as teacher"
                    :async-data="[
                        'api' => route('userSearch.search'),
                        'credentials' => 'include',
                    ]"
                    option-label="name"
                    option-value="id"
                    wire:model="user_id"/>
                <div class="mb-6">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
                </div>
            </form>
        </x-card>
    </div>
</flux:container>
