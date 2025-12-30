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

**Build Star Ratings. Simply and Powerfully.**

Laravel Star simplifies star rating management in Laravel applications. Stop creating separate tables for each rating type and start building rating systems with confidence. It provides a modern, flexible package that allows your Eloquent models to handle star rating functionality (e.g., 1 to 5 stars)—perfect for building e-commerce product ratings, review systems, and content quality assessment. This is where powerful rating management meets developer-friendly simplicity—giving you complete control over star ratings without the complexity.

## Why Laravel Star?

### Simple API

Laravel Star provides a clean, intuitive API for managing star ratings. Add, update, remove, and query ratings with simple method calls—no complex queries or manual relationship management.

### Flexible Rating Scale

Support any rating scale you need: 1-5 stars, 0-10, or any custom range. The package is fully configurable to match your application's requirements.

### Anonymous Ratings

Support both authenticated user ratings and anonymous device-based ratings. Perfect for applications where users can rate without logging in, or where you need to track ratings by device.

### Polymorphic Relationships

Use star ratings on any Eloquent model through polymorphic relationships. Products, articles, reviews, services—anything can be starable, and any model can be a starrer.

## What is Star Rating Management?

Star rating management is the process of allowing users or devices to rate content using a numeric scale (typically 1-5 stars). Traditional approaches often involve:

- Creating separate tables for each rating type
- Writing complex queries to calculate averages
- Managing rating state manually
- Duplicating code across different models

Laravel Star solves these challenges by providing:

- **Unified System**: Single table for all star ratings
- **Polymorphic Design**: Works with any model
- **Simple API**: Clean methods for all operations
- **Event Integration**: Built-in events for extensibility
- **Query Helpers**: Easy methods for common queries

Consider an e-commerce platform where customers can rate products from 1 to 5 stars. With Laravel Star, you can add ratings programmatically, calculate average ratings automatically, get rating distributions, track ratings by user or device, and integrate with notification systems through events. The power of star rating management lies not only in flexible rating scales but also in making it easy to query, track, and manage throughout your application.

## What Awaits You?

By adopting Laravel Star, you will:

- **Build rating systems** - Add star ratings to products, articles, and any content
- **Simplify rating management** - Single API for all rating operations
- **Support anonymous users** - Track ratings by device without authentication
- **Improve user trust** - Display average ratings and rating distributions
- **Enable flexible scales** - Use any rating scale that fits your needs
- **Maintain clean code** - Simple, intuitive API that follows Laravel conventions

## Quick Start

Install Laravel Star via Composer:

```bash
composer require jobmetric/laravel-star
```

Then publish the migration and run it:

```bash
php artisan vendor:publish --tag=star-migrations
php artisan migrate
```

## Documentation

Ready to transform your Laravel applications? Our comprehensive documentation is your gateway to mastering Laravel Star:

**[📚 Read Full Documentation →](https://jobmetric.github.io/packages/laravel-star/)**

The documentation includes:

- **Getting Started** - Quick introduction and installation guide
- **HasStar** - Trait for models that can receive star ratings
- **CanStar** - Trait for models that can give star ratings
- **Star Model** - Eloquent model for storing star ratings
- **Events** - Hook into rating lifecycle
- **Querying** - Methods for counting, averaging, and summarizing ratings
- **Real-World Examples** - See how it works in practice

## Contributing

Thank you for participating in `laravel-star`. A contribution guide can be found [here](CONTRIBUTING.md).

## License

The `laravel-star` is open-sourced software licensed under the MIT license. See [License File](LICENCE.md) for more information.
