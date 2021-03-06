<?php

namespace App\Repositories;

use App\Post;
use App\Contracts\PostsRepositoryContract;

class PostsCacheRepository extends BaseCacheRepository implements PostsRepositoryContract
{
    /**
     * Cache tag.
     *
     * @var string
     */
    public static $tag = Post::class;

    /**
     * @var PostsRepository
     */
    protected $next;

    /**
     * Constructor.
     *
     * @param PostsRepository $repository
     */
    public function __construct(PostsRepository $repository)
    {
        $this->next = $repository;
    }

    /**
     * Get posts.
     *
     * @param array $data
     *
     * @return Paginator
     */
    public function list(array $data)
    {
        return $this->remember($this->keyFromData($data), function () use ($data) {
            return $this->next->list($data);
        });
    }

    /**
     * Store a post.
     *
     * @param array $data
     *
     * @return Post
     */
    public function store(array $data)
    {
        $this->next->store($data);
    }

    /**
     * Get a post.
     *
     * @param string $id
     *
     * @return Post
     */
    public function get($id)
    {
        return $this->remember($id, function () use ($id) {
            return $this->next->get($id);
        });
    }

    /**
     * Update a post.
     *
     * @param string $id
     * @param array  $data
     *
     * @return Post
     */
    public function update($id, array $data)
    {
        // The resource has been updated, we no longer need the existing cache.
        app('cache')->tags(self::$tag)->forget($id);

        return $this->remember($id, function () use ($id, $data) {
            return $this->next->update($id, $data);
        });
    }

    /**
     * Delete a post.
     *
     * @param string $id
     */
    public function delete($id)
    {
        // The resource has been removed, we can forget about it.
        app('cache')->tags(self::$tag)->forget($id);

        $this->next->delete($id);
    }
}
