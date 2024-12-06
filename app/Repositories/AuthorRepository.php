<?php

namespace App\Repositories;

use App\Enums\PostStatusEnum;
use App\Http\Dto\AuthorListDto;
use App\Models\Author;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AuthorRepository
{
    private Builder $query;

    public function __construct(Author $author)
    {
        $this->query = Author::query();
    }

    /**
     * Get list
     *
     * @param AuthorListDto $request
     * @return array
     */
    public function getList(AuthorListDto $request): array
    {
        $result = [];

        //joins
        $this->addJoins($request->getColumns());

        //columns
        $this->addColumns($request->getColumns());

        //filters
        if (!empty($request->getFilter())) {
            $this->addFilters($request->getFilter());
        }

        //group by
        $author = new Author();
        $this->query->groupBy([
            $author->getTable() . '.id',
            $author->getTable() . '.name',
            $author->getTable() . '.last_name',
            $author->getTable() . '.gender',
        ]);

        //The optional flag that the frontend can send obliges you to return the total number of users and number of users that match the current filter.
        if ($request->getNeedTotal()) {
            $result['total'] = \DB::table(\DB::raw("({$this->query->toSql()}) as query"))
                ->mergeBindings($this->query->getQuery())
                ->count();
        }

        //offset
        $this->query->offset($request->getOffset());

        //limit
        $this->query->limit($request->getLimit());

        //sort
        $this->query->orderBy($request->getSortBy(), $request->getSortOrder());

        //items
        $result['items'] = $this->query->get()->toArray();

        return $result;
    }

    /**
     * Joins
     *
     * @param array $columns
     * @return void
     */
    private function addJoins(array $columns)
    {
        $author = new Author();
        $post = new Post();

        //posts
        if (in_array('count_posts', $columns)) {
            $this->query->leftJoin($post->getTable() . ' as posts', function ($join) use ($author) {
                $join->on('posts.author_id', '=', $author->getTable() . '.id');
            });
        }

        //posts_last_month
        if (in_array('count_posts', $columns)) {
            $this->query->leftJoin($post->getTable() . ' as posts_last_month', function ($join) use ($author) {
                $join->on('posts_last_month.author_id', '=', $author->getTable() . '.id')
                    ->where('posts_last_month.date', '>=', Carbon::now()->subDays(30));
            });
        }

        //published_posts
        if (in_array('average_rating_published_posts', $columns)) {
            $this->query->leftJoin($post->getTable() . ' as published_posts', function ($join) use ($author) {
                $join->on('published_posts.author_id', '=', $author->getTable() . '.id')
                    ->where('published_posts.status', PostStatusEnum::PUBLISHED->value);
            });
        }

        //published_posts_last_month
        if (in_array('average_rating_published_posts_last_month', $columns)) {
            $this->query->leftJoin($post->getTable() . ' as published_posts_last_month', function ($join) use ($author) {
                $join->on('published_posts_last_month.author_id', '=', $author->getTable() . '.id')
                    ->where('published_posts_last_month.date', '>=', Carbon::now()->subDays(30));
            });
        }
    }

    /**
     * Columns
     *
     * @param array $columns
     * @return void
     */
    private function addColumns(array $columns)
    {
        $author = new Author();
        $post = new Post();

        foreach ($columns as $column) {
            switch ($column) {
                case 'full_name':
                    $this->query->selectRaw('CONCAT(`'.$author->getTable() . '`.`last_name`, " ", `'.$author->getTable() . '`.`name`) as `full_name`');
                    break;
                case 'gender':
                    $this->query->addSelect($author->getTable() . '.gender  as gender');
                    break;
                case 'count_posts':
                    $this->query->selectRaw('count(posts.id) as count_posts');
                    break;
                case 'count_posts_last_month':
                    $this->query->selectRaw('count(posts_last_month.id) as count_posts_last_month');
                    break;
                case 'average_rating_published_posts':
                    $this->query->selectRaw('AVG(published_posts.rating) as average_rating_published_posts');
                    break;
                case 'average_rating_published_posts_last_month':
                    $this->query->selectRaw('AVG(published_posts_last_month.rating) as average_rating_published_posts_last_month');
                    break;
                case 'title_last_published_post':
                    $this->query->selectRaw('(select title from ' . $post->getTable() . ' P WHERE P.id = (select id from ' . $post->getTable() . ' P2 where P2.author_id = '.$author->getTable() . '.id order by date desc limit 1) limit 1) as title_last_published_post');
                    break;
                case 'content_last_published_post':
                    $this->query->selectRaw('(select content from ' . $post->getTable() . ' P WHERE P.id = (select id from ' . $post->getTable() . ' P2 where P2.author_id = '.$author->getTable() . '.id order by date desc limit 1) limit 1) as content_last_published_post');
                    break;
            }
        }
    }

    /**
     * Filters
     *
     * @param array $filters
     * @return void
     */
    private function addFilters(array $filters)
    {
        foreach ($filters as $column => $value) {
            switch ($column) {
                case 'full_name':
                case 'title_last_published_post':
                case 'content_last_published_post':
                    $this->query->havingRaw($column .' like "%'.$value.'%"');
                    break;
                case 'gender':
                    $this->query->where($column, $value);
                    break;
                case 'count_posts':
                case 'count_posts_last_month':
                case 'average_rating_published_posts':
                case 'average_rating_published_posts_last_month':
                    $this->query->havingRaw($column .' >= '.$value);
                    break;
            }
        }
    }
}
