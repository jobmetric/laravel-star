<?php

namespace JobMetric\Star\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JobMetric\Star\Tests\Stubs\Factories\ArticleFactory;
use JobMetric\Star\HasStar;

/**
 * @property int $id
 * @property string $title
 * @property string $status
 *
 * @method static create(string[] $array)
 */
class Article extends Model
{
    use HasFactory, HasStar;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'status'
    ];
    protected $casts = [
        'title' => 'string',
        'status' => 'string',
    ];

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }
}
