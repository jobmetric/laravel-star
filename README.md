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

# Laravel Star

A modern, flexible, and test-covered Laravel package that allows your models to handle **star rating** functionality (e.g., 1 to 5 stars).  
This package provides a clean API for both **starable** (e.g., articles, posts) and **starrer** (e.g., users, devices) models.

---

## 💾 Installation

Install via composer:

```bash
composer require jobmetric/laravel-star
```

Then publish and run the migration:

```bash
php artisan migrate
```

---

## ✨ Usage

### Step 1: Add `HasStar` to your starable model (e.g., `Article`)

```php
use JobMetric\Star\HasStar;

class Article extends Model
{
    use HasStar;
}
```

### Step 2: Add `CanStar` to your starrer model (e.g., `User`)

```php
use JobMetric\Star\CanStar;

class User extends Model
{
    use CanStar;
}
```

---

## ✅ Main Features

### Add or Update a Star Rating

```php
$article->addStar(4, $user);
```

You can also pass extra options like device ID:

```php
$article->addStar(5, null, ['device_id' => 'abc-123']);
```

### Remove a Star Rating

```php
$article->removeStar($user);
$article->removeStar(null, 'abc-123');
```

### Check if a Star Exists

```php
$article->hasStar($user); // true/false
```

### Get Star Count and Average

```php
$article->starCount(); // e.g., 10
$article->starAvg();   // e.g., 4.3
```

### Get Summary

```php
$article->starSummary(); 
// => collect([5 => 3, 4 => 5, 3 => 2])
```

### Get Latest Stars

```php
$article->latestStars(5); // returns latest 5 stars
```

### Forget Stars (All for a user or device)

```php
$article->forgetStars($user);
```

---

## 🎯 Conditional Methods

### Rating Checks

```php
$article->isRatedAs(4, $user);     // true
$article->isRatedAbove(3, $user);  // true
$article->isRatedBelow(5, $user);  // true
```

### Get Rated Value

```php
$article->getRatedValue($user); // e.g., 4
```

---

## 📦 CanStar Feature Set

### Check if a model has starred something

```php
$user->hasStarred($article); // true/false
```

### Get rate value

```php
$user->starredRate($article); // e.g., 5
```

### Remove star from a model

```php
$user->removeStarFrom($article);
```

### Count by Rate or Total

```php
$user->countStarGiven(5); // e.g., 2
$user->totalStarsGiven(); // e.g., 12
```

### Summary of Ratings Given

```php
$user->starSummary(); 
// => collect([5 => 4, 3 => 2])
```

### Models that user has starred

```php
$user->starredItems(); // Collection of models
$user->starredItems(Article::class);
```

### Stars to specific model type

```php
$user->starsToType(Article::class); // Collection of stars
```

### Latest Stars Given

```php
$user->latestStarsGiven(5); // Collection of latest 5 stars
```

## 🧱 Star Model Columns

| Field           | Description                                 |
|-----------------|---------------------------------------------|
| starable_type   | Polymorphic class of starable (e.g., Post)  |
| starable_id     | ID of the starable model                    |
| starred_by_type | Polymorphic class of starrer (e.g., User)   |
| starred_by_id   | ID of the starrer                           |
| rate            | Star rating (e.g., 1 to 5)                  |
| ip              | IP address of the request                   |
| device_id       | Optional device identifier                  |
| source          | Source of the request (e.g., web, app, api) |
| created_at      | Timestamp when the star was created         |
| updated_at      | Timestamp when the star was last updated    |

## 🧪 Events

| Event                 | Triggered When                       |
| --------------------- | ------------------------------------ |
| **StarAddEvent**      | A new star is created and saved      |
| **StarRemovingEvent** | A star is about to be deleted        |
| **StarRemovedEvent**  | A star has been successfully deleted |
| **StarUpdatingEvent** | A star is about to be updated        |
| **StarUpdatedEvent**  | A star has been successfully updated |


## Contributing

Thank you for considering contributing to the Laravel Star! The contribution guide can be found in the [CONTRIBUTING.md](https://github.com/jobmetric/laravel-star/blob/master/CONTRIBUTING.md).

## License

The MIT License (MIT). Please see [License File](https://github.com/jobmetric/laravel-star/blob/master/LICENCE.md) for more information.
