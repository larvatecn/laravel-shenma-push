# laravel-shenma-push
神马自动推送

[![Packagist](https://img.shields.io/packagist/l/larva/laravel-shenma-push.svg?maxAge=2592000)](https://packagist.org/packages/larva/laravel-shenma-push)
[![Total Downloads](https://img.shields.io/packagist/dt/larva/laravel-shenma-push.svg?style=flat-square)](https://packagist.org/packages/larva/laravel-shenma-push)


## Installation

```bash
composer require larva/laravel-shenma-push
```

## Config

```php
//add services.php
    'shenma'=>[
        'site' => '',//网站域名HTTPS网站需要包含 https://
        'username' => '',
        'token' => '',
    ]
```

## 使用
```php
\Larva\Shenma\Push\ShenmaPing::push('https://www.aa.com');
```