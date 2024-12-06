<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property int $name
 * @property string $last_name
 * @property string $gender
 * @property string $full_name
 * @property int $count_posts
 * @property int $count_posts_last_month
 * @property int $average_rating_published_posts
 * @property int $average_rating_published_posts_last_month
 * @property string $title_last_published_post
 * @property string $content_last_published_post
 *
 * @property-read Collection|Post[]|null $posts
 */
class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'gender',
    ];

    protected $guarded = [
        'id',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
