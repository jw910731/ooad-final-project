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
        $get_id = function($user){
            return $user->id;
        };
        $teachers = User::factory()->count(3)->create()->map($get_id)->toArray();
        $students = User::factory()->count(3)->create()->map($get_id)->toArray();
        $teaching_assistants = User::factory()->count(3)->create()->map($get_id)->toArray();
        $this->actingAs($user);
        $response = Volt::test('courses.create')
            ->set('userTeacher_id', $teachers)
            ->set('userStudent_id', $students)
            ->set('userTA_id', $teaching_assistants)
            ->set('title', 'test')
            ->set('description', 'test')
            ->call('create');
        $response->assertHasNoErrors();
        $response->assertRedirect(route('courses.index'));
        $this->assertTrue(Course::count() > 0);
    }
}
