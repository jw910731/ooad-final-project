<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_create()
    {
        $user = User::factory()->create([
            'system_admin' => true,
        ]);
        $this->actingAs($user);
        $response = Volt::test('courses.create')
            ->set('user_id', $user->id)
            ->set('title', "test")
            ->set('description', "test")
            ->call('create');
        $response->assertHasNoErrors();
        $response->assertRedirect(route('courses.index'));
        $this->assertTrue(Course::count() > 0);
    }
}
