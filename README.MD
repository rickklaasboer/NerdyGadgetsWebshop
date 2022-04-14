<p align="center"><img src="https://www.rickklaasboer.nl/img/logo-black.svg" height="100px"/></p>

# NerdyGadgetsWebshop
The NerdyGadgets webshop made for the fictional company NerdyGadgets (https://nerdygadgets-next.dev.rickklaasboer.nl/). Originally, this was a group project. However this has been mirrored without the commits of these people for privacy concerns. 

## Installation
Prerequisites:
- [PHP](https://www.php.net/) >= 7.4
- [Composer](https://getcomposer.org/) >= 1.10.15 (not yet Composer v2 compatible)
- [NPM](https://nodejs.org/en/) (comes with NodeJS) or [Yarn](https://yarnpkg.com/) (< v2)
- PHP, Composer and NPM/Yarn in your PATH

First, copy `.env.example` and rename it to `.env`, you can do this by running:

```shell script
cp .env.example .env # Linux and macOS
copy .env.example .env # Windows
```

Then in your root directory, run:
```shell script
composer install
npm install # or yarn
npm run dev # or yarn dev
```

Next, you should configure your environment variables in `.env`. They should look something like this:
```dotenv
APP_LANGUAGE=en # the default app language (used by Translator)
APP_ENV=dev # the app environment (will probably always be dev on your machine)

DB_HOST=localhost # where your database server is listening
DB_DATABASE=nerdygadgets # the name of your database
DB_USER=root # username
DB_PASSWORD=root # password
```

## Development
To run a development server, in your root directory, run:
```shell script
php -S localhost:8080 -t public
```
This will launch a development server at http://localhost:8080

You should also be able to run this within XAMPP,
but you will need to setup a custom vhost that only exposes the public directory.
(otherwise people will be able to access our `.env` file and such).

## Deployment
Deployment steps

```shell script
composer install
npm run prod # or yarn prod
```

# License (MIT)
Copyright 2022 Rick Klaasboer

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Disclaimer
This project was an assignment for Windesheim and it may be possible that the code is not ready for production usage, does not adhere to some formatting standards and/or is bilingual (mix of English and Dutch).
