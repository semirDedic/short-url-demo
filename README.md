# Short Url generator


A simple short url generator app built with Laravel and Vue.js. The main objective of this project is to provide an example application for those who are starting with Laravel and Vue.js.

  - Check if the url is safe using Google Safe Browsing lookup.
  - Create short URL.
  - Redirect short link to original url.

### Tech

Technologies used in this project:

* [Laravel](https://github.com/laravel/laravel) - The Laravel PHP framework.
* [Vue.js](https://github.com/vuejs) - Vue.js.
* [Tailwind CSS](https://tailwindcss.com/) - Tailwind CSS.
* [Inertia.js](hhttps://github.com/inertiajs) - Inertia.js.
* [Google Safe Browsing API](https://developers.google.com/safe-browsing/v4) - Google Safe Browsing API.


### Requirements

* [PHP 8.2+](https://www.php.net/) - PHP version 8.2 or greater.
* [Composer](https://getcomposer.org/download/) - Latest version of composer v2 or greater.
* [npm](https://www.npmjs.com/) - Latest version of npm v10 or greater.
* [Google Safe Browsing API](https://developers.google.com/safe-browsing/v4/lookup-api) - Google Safe Browsing API.

### Before Installation

1. Register your project on [Google](https://developers.google.com/safe-browsing/v4/get-started)

### Installation

1. Clone the `main` branch of this repo

2. Install the dependencies and devDependencies:

```sh
$ cd short-url-demo
$ composer install
$ npm install
$ npm run dev
```

3. Create your .env file and generate the application key:

```sh
$ cp .env.example .env
$ php artisan key:generate
```

4. Copy your API key from PLACEKIT and place it in your .env file:

```sh
GOOGLE_API_KEY=set_key_here
```

5. Run migrations (mysql table name "demo_short_url") and start the server:

```sh
$ php artisan migrate
$ php artisan serve
```

### Tests

Url that will fail google lookup api:

```
https://testsafebrowsing.appspot.com/s/malware.html
```

License
----

MIT
