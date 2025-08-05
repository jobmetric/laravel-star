<?php

namespace JobMetric\Star\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use JobMetric\Star\Models\Star;
use JobMetric\Star\Tests\Stubs\Models\Article;
use JobMetric\Star\Tests\Stubs\Models\User;
use JobMetric\Star\Tests\TestCase as BaseTestCase;
use Throwable;

class CanStarTest extends BaseTestCase
{
    /**
     * @throws Throwable
     */
    public function test_stars_given_relationship()
    {
        $user = new User;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphMany::class, $user->starsGiven());
    }

    /**
     * @throws Throwable
     */
    public function test_has_starred()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->assertFalse($user->hasStarred($article));

        $article->addStar(4, $user);

        $this->assertTrue($user->hasStarred($article));
    }

    /**
     * @throws Throwable
     */
    public function test_starred_rate()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->assertNull($user->starredRate($article));

        $article->addStar(5, $user);

        $this->assertEquals(5, $user->starredRate($article));
    }

    /**
     * @throws Throwable
     */
    public function test_remove_star_from()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(3, $user);

        $this->assertTrue($user->removeStarFrom($article));

        $this->assertDatabaseMissing('stars', [
            'starred_by_type' => User::class,
            'starred_by_id' => $user->id,
            'starable_type' => Article::class,
            'starable_id' => $article->id,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_count_star_given()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(2, $user);

        $this->assertEquals(1, $user->countStarGiven(2));
        $this->assertEquals(0, $user->countStarGiven(5));
    }

    /**
     * @throws Throwable
     */
    public function test_total_stars_given()
    {
        $user = User::factory()->create();
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        $article1->addStar(1, $user);
        $article2->addStar(4, $user, ['device_id' => 'device-id']);

        $this->assertEquals(2, $user->totalStarsGiven());
    }

    /**
     * @throws Throwable
     */
    public function test_star_summary()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(3, $user);
        $article->addStar(3, null, ['device_id' => 'x']);
        $article->addStar(5, null, ['device_id' => 'y']);

        $summary = $user->starSummary();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $summary);
        $this->assertEquals(1, $summary->get(3));
        $this->assertNull($summary->get(5));
    }


    /**
     * @throws Throwable
     */
    public function test_starred_items()
    {
        $user = User::factory()->create();
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        $article1->addStar(2, $user);
        $article2->addStar(4, $user);

        $items = $user->starredItems();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertCount(2, $items);
        $this->assertTrue($items->contains(fn($item) => $item->is($article1)));
        $this->assertTrue($items->contains(fn($item) => $item->is($article2)));

        $filtered = $user->starredItems(Article::class);
        $this->assertCount(2, $filtered);
    }

    /**
     * @throws Throwable
     */
    public function test_stars_to_type()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(5, $user);

        $stars = $user->starsToType(Article::class);

        $this->assertInstanceOf(Collection::class, $stars);
        $this->assertCount(1, $stars);
        $this->assertEquals(5, $stars->first()->rate);
    }

    /**
     * @throws Throwable
     */
    public function test_latest_stars_given()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(3, $user);
        $article->addStar(4, $user, ['device_id' => 'extra']);

        $latest = $user->latestStarsGiven();

        $this->assertInstanceOf(Collection::class, $latest);
        $this->assertGreaterThan(0, $latest->count());

        foreach ($latest as $star) {
            $this->assertInstanceOf(Star::class, $star);
            $this->assertEquals($user->id, $star->starred_by_id);
        }
    }
}
