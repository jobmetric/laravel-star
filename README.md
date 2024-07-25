[contributors-shield]: https://img.shields.io/github/contributors/jobmetric/laravel-star.svg?style=for-the-badge
[contributors-url]: https://github.com/jobmetric/laravel-star/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/jobmetric/laravel-star.svg?style=for-the-badge&label=Fork
[forks-url]: https://github.com/jobmetric/laravel-star/network/members
[stars-shield]: https://img.shields.io/github/stars/jobmetric/laravel-star.svg?style=for-the-badge
[stars-url]: https://github.com/jobmetric/laravel-star/stargazers
[license-shield]: https://img.shields.io/github/license/jobmetric/laravel-star.svg?style=for-the-badge
[license-url]: https://github.com/jobmetric/laravel-star/blob/master/LICENCE.md
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-blue.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/majidmohammadian

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

# Star for laravel

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

```php
$post->withStarCount();
```

#### withStarAvg

load star avg after a model loaded

#### withStar

load star or disStar after model loaded

#### withStars

load stars after models loaded

#### isStaredStatusBy

is stared by user

```php
$user_id = 1;

$post->isStaredStatusBy($user_id);
```

#### forgetStar

forget star

```php
$user_id = 1;

$post->forgetStar($user_id);
```

#### forgetStars

forget stars

```php
$post->forgetStars();
```

## Contributing

Thank you for considering contributing to the Laravel Star! The contribution guide can be found in the [CONTRIBUTING.md](https://github.com/jobmetric/laravel-star/blob/master/CONTRIBUTING.md).

## License

The MIT License (MIT). Please see [License File](https://github.com/jobmetric/laravel-star/blob/master/LICENCE.md) for more information.
