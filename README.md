# Like for laravel

This is a star rating management package for any object in Laravel that you can use in your projects.

## Install via composer

Run the following command to pull in the latest version:
```bash
composer require jobmetric/laravel-star
```

## Documentation

This package evolves every day under continuous development and integrates a diverse set of features. It's a must-have asset for Laravel enthusiasts and provides a seamless way to align your projects with basic star and rating models.

In this package, you can use it seamlessly with any model that needs stars and ratings.

Now let's go to the main function.

>#### Before doing anything, you must migrate after installing the package by composer.

```bash
php artisan migrate
```

Meet the `HasStar` class, meticulously designed for integration into your model. This class automates essential tasks, ensuring a streamlined process for:

In the first step, you need to connect this class to your main model.

```php
use JobMetric\Like\HasStar;

class Post extends Model
{
    use HasStar;
}
```

## How is it used?

You can now use the `HasStar` class for your model. The following example shows how to create a new post with ratings and stars:

```php
$post = Post::create([
    'status' => 'published',
]);

$user_id = 1;

$post->starIt($user_id, $star = 5);
```

> The `starIt` function is used to rate the post. The first parameter is the user id, and the second parameter is the star rating.

### Now we go to the functions that we have added to our model.

#### starTo

star has one relationship

#### starsTo

star has many relationships

#### starCount

get star count

#### starAvg

get star average

#### withStarCount

load star count after a model loaded

#### withStarAvg

load star avg after a model loaded

#### withStar

load star or disStar after model loaded

#### withStars

load stars after models loaded

#### isStaredStatusBy

is stared by user

#### forgetStar

forget star

#### forgetStars

forget stars
