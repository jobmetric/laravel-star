<?php

namespace JobMetric\Star\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Event;
use JobMetric\Star\Events\StarAddEvent;
use JobMetric\Star\Exceptions\MaxStarException;
use JobMetric\Star\Exceptions\MinStarException;
use JobMetric\Star\Models\Star;
use JobMetric\Star\Tests\Stubs\Models\Article;
use JobMetric\Star\Tests\Stubs\Models\User;
use JobMetric\Star\Tests\TestCase as BaseTestCase;
use Throwable;

class HasStarTest extends BaseTestCase
{
    /**
     * @throws Throwable
     */
    public function test_article_trait_relationship()
    {
        $article = new Article;

        $this->assertInstanceOf(MorphMany::class, $article->stars());
    }

    /**
     * @throws Throwable
     */
    public function test_add_star()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        Event::fake();

        $star = $article->addStar(4, $user);

        $this->assertInstanceOf(Star::class, $star);
        $this->assertEquals(4, $star->rate);
        $this->assertEquals($user->id, $star->starred_by_id);
        $this->assertEquals(User::class, $star->starred_by_type);

        Event::assertDispatched(StarAddEvent::class);

        $this->assertDatabaseHas('stars', [
            'starred_by_type' => User::class,
            'starred_by_id' => $user->id,
            'starable_type' => Article::class,
            'starable_id' => $article->id,
            'rate' => 4,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_add_star_with_min_exception()
    {
        $this->expectException(MinStarException::class);

        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(0, $user);
    }

    /**
     * @throws Throwable
     */
    public function test_add_star_with_max_exception()
    {
        $this->expectException(MaxStarException::class);

        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(6, $user);
    }

    /**
     * @throws Throwable
     */
    public function test_add_star_with_device_id_only_should_pass()
    {
        $article = Article::factory()->create();

        $article->addStar(5, null, ['device_id' => 'test-device-id']);

        $this->assertDatabaseHas('stars', [
            'device_id' => 'test-device-id',
            'rate' => 5,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_add_star_with_ip()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $star = $article->addStar(3, $user, ['ip' => '192.168.1.1']);

        $this->assertEquals('192.168.1.1', $star->ip);

        $this->assertDatabaseHas('stars', [
            'ip' => '192.168.1.1',
            'rate' => 3,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_add_star_with_source()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $star = $article->addStar(2, $user, ['source' => 'web']);

        $this->assertEquals('web', $star->source);

        $this->assertDatabaseHas('stars', [
            'source' => 'web',
            'rate' => 2,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_remove_star()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(4, $user);

        $removed = $article->removeStar($user);

        $this->assertTrue($removed);

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
    public function test_has_star()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(5, $user);

        $this->assertTrue($article->hasStar($user));
        $this->assertFalse($article->hasStar(null, 'non-existent-device'));
    }

    /**
     * @throws Throwable
     */
    public function test_is_rated_as_above_below()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(4, $user);

        $this->assertTrue($article->isRatedAs(4, $user));
        $this->assertTrue($article->isRatedAbove(3, $user));
        $this->assertTrue($article->isRatedBelow(5, $user));
        $this->assertFalse($article->isRatedAs(3, $user));
    }

    /**
     * @throws Throwable
     */
    public function test_get_rated_value()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(5, $user);

        $this->assertEquals(5, $article->getRatedValue($user));
    }

    /**
     * @throws Throwable
     */
    public function test_star_summary()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(3, $user);
        $article->addStar(5, null, ['device_id' => 'test-device-id']);

        $summary = $article->starSummary();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $summary);
        $this->assertEquals(1, $summary->get(3));
        $this->assertEquals(1, $summary->get(5));
    }

    /**
     * @throws Throwable
     */
    public function test_star_count_and_avg()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(4, $user);
        $article->addStar(2, null, ['device_id' => 'test-device-id']);

        $this->assertEquals(2, $article->starCount());
        $this->assertEquals(3.0, $article->starAvg());
    }

    /**
     * @throws Throwable
     */
    public function test_latest_stars()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(2, $user);
        $article->addStar(5, null, ['device_id' => 'another-device']);

        $latest = $article->latestStars(2);

        $this->assertInstanceOf(Collection::class, $latest);
        $this->assertCount(2, $latest);

        foreach ($latest as $star) {
            $this->assertInstanceOf(Star::class, $star);
            $this->assertEquals($article->id, $star->starable_id);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_forget_stars()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->addStar(2, $user);
        $article->addStar(3, null, ['device_id' => 'some-device']);

        $count = $article->forgetStars($user);

        $this->assertEquals(1, $count);

        $this->assertDatabaseMissing('stars', [
            'starred_by_id' => $user->id,
        ]);
    }
}
