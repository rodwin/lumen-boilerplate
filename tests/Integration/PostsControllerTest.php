<?php

use App\Post;
use App\User;

class PostsControllerTest extends TestCase
{
    /** @test */
    public function guest_cannot_list_posts()
    {
        $this->json('GET', '/posts')
            ->seeStatusCode(401);
    }

    /** @test */
    public function user_can_list_posts()
    {
        $this->actingAs(factory(User::class)->create())
            ->json('GET', '/posts')
            // Validate that a paginator is returned.
            ->seeJsonStructure(['data', 'links', 'meta']);
    }

    /** @test */
    public function guest_cannot_store_post()
    {
        $this->json('POST', '/posts')
            ->seeStatusCode(401);
    }

    /** @test */
    public function user_can_store_post()
    {
        $this->actingAs(factory(User::class)->create())
            ->json('POST', '/posts', [
                'title'   => 'Lorem',
                'content' => 'Ipsum',
            ])
            ->seeStatusCode(201);
    }

    /** @test */
    public function user_cannot_store_post_without_title()
    {
        $this->actingAs(factory(User::class)->create())
            ->json('POST', '/posts', [
                'content' => 'Ipsum',
            ])
            ->seeValidationError('title');
    }

    /** @test */
    public function user_cannot_store_post_with_an_already_used_title()
    {
        $user = factory(User::class)->create();

        factory(Post::class)->create([
            'user_id' => $user->id,
            'title'   => 'Lorem',
            'content' => 'Ipsum',
        ]);

        $this->actingAs($user)
            ->json('POST', '/posts', [
                'title'   => 'Lorem',
                'content' => 'Ipsum',
            ])
            ->seeValidationError('title');
    }

    /** @test */
    public function user_cannot_store_post_without_content()
    {
        $this->actingAs(factory(User::class)->create())
            ->json('POST', '/posts', [
                'title' => 'Lorem',
            ])
            ->seeValidationError('content');
    }

    /** @test */
    public function guest_cannot_read_post()
    {
        $this->json('GET', '/posts/some-id')
            ->seeStatusCode(401);
    }

    /** @test */
    public function user_can_read_post()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('GET', '/posts/' . $post->id)
            ->seeJsonStructure([
                // This relationship should be loaded.
                'data' => ['user'],
            ])
            ->seeJson([
                'id'         => $post->id,
                'created_at' => (string) $post->created_at,
                'updated_at' => (string) $post->updated_at,
                'title'      => $post->title,
                'content'    => $post->content,
            ]);
    }

    /** @test */
    public function guest_cannot_update_post()
    {
        $this->json('PUT', '/posts/some-id')
            ->seeStatusCode(401);
    }

    /** @test */
    public function user_can_update_post()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $new_title   = 'Hello';
        $new_content = 'World';

        $this->actingAs($user)
            ->json('PUT', '/posts/' . $post->id, [
                'title'   => $new_title,
                'content' => $new_content,
            ])
            ->seeJsonStructure([
                'data' => ['user'],
            ])
            // Make sure we get fresh data.
            ->seeJson([
                'title'      => $new_title,
                'content'    => $new_content,
            ]);
    }

    /** @test */
    public function user_cannot_update_post_without_title()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('PUT', '/posts/' . $post->id, [
                'content' => 'Foo',
            ])
            ->seeValidationError('title');
    }

    /** @test */
    public function user_cannot_update_post_with_an_already_used_title()
    {
        $user = factory(User::class)->create();

        $first_post  = factory(Post::class)->create(['user_id' => $user->id]);
        $second_post = factory(Post::class)->create([
            'user_id' => $user->id,
            'title'   => 'Foo',
        ]);

        $this->actingAs($user)
            ->json('PUT', '/posts/' . $first_post->id, [
                'title'   => $second_post->title,
                'content' => 'Bar',
            ])
            ->seeValidationError('title');
    }

    /** @test */
    public function user_cannot_update_post_without_content()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('PUT', '/posts/' . $post->id, [
                'title' => 'Foo',
            ])
            ->seeValidationError('content');
    }

    /** @test */
    public function guest_cannot_delete_post()
    {
        $this->json('DELETE', '/posts/some-id')
            ->seeStatusCode(401);
    }

    /** @test */
    public function user_can_delete_post()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('DELETE', '/posts/' . factory(Post::class)->create(['user_id' => $user->id])->id)
            ->seeStatusCode(204);
    }
}
