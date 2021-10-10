![로고](https://github.com/xpressengine/xe_cli/blob/master/logo.png?raw=true)
# XE CLI

## 설치방법
XpressEngine3이(가) 설치된 `privates` 디렉토리에 들어가서 아래 명령어를 통해 플러그인을 cli 환경에서 설치합니다.
```
cd privates
git clone https://github.com/xpressengine/xe_cli.git
```

XE CLI Repository가 복제되었다면 XpressEngine3이(가) 설치된 루트로 이동하여 아래 명령어를 실행합니다.
```
php artisan plugin:private_install xe_cli
```

이후 XpressEngine3가 설치된 웹 `/settings` 페이지에 접속하여 플러그인을 활성화 하면 모든 설치가 완료됩니다.


## 기능
해당 플러그인에서 제공하는 PHP Artisan Command 목록에 대한 소개를 진행합니다.


### 위젯 생성

```
php artisan make:widget {plugin_name} {widget_name}
```

- plugin_name : 위젯을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 위젯 이름


### 회원 가입/로그인 스킨 생성

```
php artisan make:user-auth-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 회원 가입/로그인 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 회원 가입/로그인 스킨의 이름


### 마이페이지 스킨 생성

```
php artisan make:user-settings-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 마이페이지 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 마이페이지 스킨의 이름


### 프로필 스킨 생성

```
php artisan make:user-profile-skin {plugin_name} {widget_name}
```

- plugin_name : 새로운 프로필 스킨을 생성할 플러그인 이름
- widget_name : 새롭게 생성할 프로필 스킨의 이름


### 에러 스킨 생성

```
php artisan make:error-skin {plugin_name} {widget_name}
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
### database에 session 관리하기

```
php artisan migrate:session-database
```

세션을 데이터베이스에 저장하기 위해 필요한 Table, Config를 프로젝트에 추가 시킵니다.

### database애 queue 관리하기

```
php artisan migrate:queue-database
```

큐를 데이터베이스에 저장하기 위해 필요한 Table, Config를 프로젝트에 추가 시킵니다.

