# spark_command

# Install
php artisan plugin:private_install spark_command

# XE3
/settings 페이지에 접속해서 플러그인을 활성화 시켜야합니다.

# Coomand
해당 플러그인에서 제공하는 Php Artisan Command 목록에 대한 소개를 진행합니다.


### 위젯 생성

```
php artisan make:widget {plugin_name} {widget_name}
```

- plugin_name : 위젯을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 위젯 이름


### 회원 가입/로그인 스킨 생성

```
php artisan make:make:user-auth-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 회원 가입/로그인 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 회원 가입/로그인 스킨의 이름


### 마이페이지 스킨 생성

```
php artisan make:make:user-settings-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 마이페이지 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 마이페이지 스킨의 이름


### 프로필 스킨 생성

```
php artisan make:make:user-profile-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 프로필 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 프로필 스킨의 이름


### 에러 스킨 생성

```
php artisan make:make:error-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 에러 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 에러 스킨의 이름

에러 스킨을 적용하기 위해선 `/config/production/view.php`에 아래 코드를 수정해야 합니다.

```
<?php

/**
 * view.php
 *
 * PHP version 7
 *
 * @category    Config
 * @license     https://opensource.org/licenses/MIT MIT
 * @link        https://laravel.com
 */

return [
	/*
	|--------------------------------------------------------------------------
	| Error View Path
	|--------------------------------------------------------------------------
	|
	| This option using by Exception/Handler.
	|  - if use file in the plugin : 'path' => 'plugin_name::view.path'
	|  - without theme : 'theme' => false
	|
	*/
    'error' => [
        'path' => 'View Path',
    ],
];

```

