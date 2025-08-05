<?php

namespace JobMetric\Star\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JobMetric\Star\CanStar;
use JobMetric\Star\Tests\Stubs\Factories\UserFactory;

/**
 * @property int $id
 * @property string $name
 *
 * @method static create(string[] $array)
 */
class User extends Model
{
    use HasFactory, CanStar;

    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
    protected $casts = [
        'name' => 'string'
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
