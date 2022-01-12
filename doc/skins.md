### * Make User Auth Skin

회원 가입/로그인 스킨을 생성해줍니다.  
(관리자 > 테마 디자인 > 글로벌 메뉴 스킨에서 사이트 내 회원 가입/로그인 스킨을 설정할 수 있습니다.)

```
php artisan xe_cli:make:userAuthSkin 
    {plugin_name : 스킨을 생성할 플러그인 이름}
    {skin_name : 스킨 이름}
```

#### 예시

```
php artisan xe_cli:make:userAuthSkin xe_cli exam
php artisan xe_cli:make:userAuthSkin xe_cli test
```

<br>

### * Make User Settings Skin

회원 가입/로그인 스킨을 생성해줍니다.  
(관리자 > 테마 디자인 > 글로벌 메뉴 스킨에서 사이트 내 마이페이지 스킨을 설정할 수 있습니다.)

```
php artisan xe_cli:make:userSettingsSkin 
    {plugin_name : 스킨을 생성할 플러그인 이름}
    {skin_name : 스킨 이름}
```

#### 예시

```
php artisan xe_cli:make:userSettingsSkin xe_cli exam
php artisan xe_cli:make:userSettingsSkin xe_cli test
```

<br>

### * Make User Profile Skin

회원 프로필 스킨을 생성해줍니다.  
(관리자 > 테마 디자인 > 글로벌 메뉴 스킨에서 사이트 내 프로필 스킨을 설정할 수 있습니다.)

```
php artisan xe_cli:make:userProfileSkin
    {plugin_name : 스킨을 생성할 플러그인 이름}
    {skin_name : 스킨 이름}
```

#### 예시

```
php artisan xe_cli:make:userProfileSkin xe_cli exam
php artisan xe_cli:make:userProfileSkin xe_cli test
```

<br>

### * Make Error Skin

에러 스킨을 적용하기 위해선 /config/production/view.php 파일을 수정해줘야 합니다.

```
php artisan xe_cli:make:errorSkin 
    {plugin_name : 새로운 에러 스킨을 생성할 플러그인 이름}
    {skin_name :  새롭게 생성할 에러 스킨의 이름}
```

/config/production/view.php 에 수정할 코드는 아래와 같습니다.
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

#### 예시

```
php artisan xe_cli:make:errorSkin xe_cli exam
php artisan xe_cli:make:errorSkin xe_cli test
```





