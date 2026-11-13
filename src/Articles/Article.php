<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Articles;

use Calexandar\Adminium\Groups\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kra8\Snowflake\HasShortflakePrimary;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class Article extends Model implements HasMedia
{
    use HasShortflakePrimary, HasTranslations, InteractsWithMedia;

    /**
     * The attributes that are translatable.
     *
     * @var list<string>
     */
    public array $translatable = [
        'title',
        'content',
        'short_description',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'group_id',
        'author_id',
        'title',
        'slug',
        'content',
        'short_description',
        'meta_title',
        'meta_description',
        'order',
        'published',
    ];

    /**
     * Get the group that this article belongs to.
     */
    // @phpstan-ignore-next-line
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user that this article belongs to.
     */
    // @phpstan-ignore-next-line
    public function author(): BelongsTo
    {
        return $this->belongsTo(adminium_user_model());
    }
}
