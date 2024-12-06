<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_author_list(): void
    {
        $params = [
            'columns' => [
                'full_name',
                'gender',
                'count_posts',
                'count_posts_last_month',
                'average_rating_published_posts',
                'average_rating_published_posts_last_month',
                'title_last_published_post',
                'content_last_published_post',
            ],
            'offset' => 0,
            'limit' => 100,
            'sort_by' => 'full_name',
            'sort_order' => 'desc',
            'filter' => [
                'full_name' => 'Kel',
                'gender' => 'female',
                'count_posts' => 0,
                'count_posts_last_month' => 0,
                'average_rating_published_posts' => 4,
//                'average_rating_published_posts_last_month' => 0,
                'title_last_published_post' => 'd',
                'content_last_published_post' => 't',
            ],
            'need_total' => true,
        ];

        $response = $this->json('GET', '/api/authors', $params);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'total',
            'items',
        ]);
    }
}
