<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_blog_post()
    {
        // 1. (Optional) Setup any data that you may need for this test.
        $user = factory(User::class)->create();

        // 2. Complete an action.
        $this->actingAs($user)
            ->json('POST', route('posts.store'), [
                'title' => 'My cool blog post',
                'description' => 'some blog text',
            ]);

        // 3. Make an assertion.
        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => 'My cool blog post',
        ]);
    }

    /** @test */
    public function a_user_can_update_a_blog_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['title' => 'test']);

        $this->actingAs($user)
            ->json('PUT', route('posts.update', $post->id), [
                'title' => 'Test title',
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test title',
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_blog_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)
            ->json('DELETE', route('posts.destroy', $post->id));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
