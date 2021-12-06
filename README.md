![로고](https://github.com/xpressengine/xe_cli/blob/master/logo.png?raw=true)
# XE CLI

## 설치방법

<details>
<summary>설치방법</summary>

<p>
	
XpressEngine3이(가) 설치된 디렉토리에 들어가서 아래 명령어를 cli 환경에서 실행합니다. 
	
```
cd privates
git clone https://github.com/xpressengine/xe_cli.git
```
	
XE CLI Repository가 복제되었다면 XpressEngine3이(가) 설치된 루트로 이동해 아래 명령어를 실행합니다.   
	
```
php artisan plugin:private_install xe_cli
```
	
이후 아래 명령어로 XE CLI 플러그인을 활성화시켜 주세요.   
	
```
php artisan plugin:private_install xe_cli
```
	
</p>
</details>

---

## 명령어

<details>
<summary>위젯 명령어</summary>

<p>

### 위젯 생성

```
php artisan xe_cli:make:widget {plugin_name} {widget_name}
```

- plugin_name : 위젯을 생성할 플러그인 이름
- widget_name : 생성할 위젯 이름 

</p>
</details>

---

<details>
<summary>스킨 명령어</summary>

<p>
	
### 회원 가입/로그인 스킨 생성

```
php artisan xe_cli:make:userAuthSkin  {plugin_name} {skin_name}
```

- plugin_name : 스킨을 생성할 플러그인 이름
- skin_name : 생설할 스킨 이름


### 마이페이지 스킨 생성

```
php artisan xe_cli:make:userSettingsSkin {plugin_name} {skin_name}
```

- plugin_name : 스킨을 생성할 플러그인 이름
- skin_name : 생성할 스킨 이름

---

### 프로필 스킨 생성

```
php artisan xe_cli:make:userProfileSkin {plugin_name} {skin_name}
```

- plugin_name : 스킨을 생성할 플러그인 이름
- skin_name : 생성할 스킨 이름
	
### 에러 스킨 생성

```
php artisan xe_cli:make:errorSkin {plugin_name} {skin_name}
```

- plugin_name : 새로운 에러 스킨을 생성할 플러그인 이름
- skin_name : 새롭게 생성할 에러 스킨의 이름

에러 스킨을 적용하기 위해선 `/config/production/view.php` 코드를 수정해야 합니다.
	
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
	

</p>
</details>

---

<details>
<summary>마이그레이션 명령어</summary>

<p>

### Session - Database Table 마이그레이션

```
php artisan xe_cli:migrate:sessionDatabase
```

세션을 데이터베이스에서 관리하기 위한 Table, Config 를 추가해줍니다.

### Queue - Database Table 마이그레이션

```
php artisan xe_cli:migrate:queueDatabase
```

큐를 데이터베이스에서 관리하기 위한 Table, Config 를 추가해줍니다.

### Make Migration Table

```
xe_cli:make:migrationTable {plugin} {name} {--model}
```

#### 예시

```
php artisan xe_cli:make:migrationTable xe_cli exam --model
php artisan xe_cli:make:migrationTable xe_cli exam
```

#### 설명
- 옵션
    - --model : 테이블 마이그레이션에 대한 모델 파일 생성


### Make Migration Resource

```
xe_cli:make:migrationResource {plugin}
```

</p>
</details>

---

<details>
<summary>헬퍼 명령어</summary>

<p>

### Move MenuItem

```
php artisan xe_cli:move:menuItem {menu} {menuItem*} {--position=}
```

### Set Menu Item's Order

```
php artisan xe_cli:setOrder:menuItem {menuItem} {position}
```

</p>
</details>

---

<details>
<summary>컨트롤러 명령어</summary>

<p>

### Make Controller

```
php artisan xe_cli:make:controller {plugin} {name}
```

### Make BackOffice Controller

```
php artisan xe_cli:make:backOfficeController {plugin} {name}
```

- 옵션
    - --structure : 상세한 내용 없이 형태만 가져와 생성해줍니다.

### Make Client Controller

```
php artisan xe_cli:make:clientController {plugin} {name}
```

</p>
</details>

---

<details>
<summary>핸들러 명령어</summary>

<p>

### Make Handler

```
php artisan xe_cli:make:handler {plugin} {name} {--structure}
```
#### 예시

```
php artisan xe_cli:make:handler xe_cli exam --structure
php artisan xe_cli:make:handler xe_cli exam
```

#### 설명
- 옵션
  - --structure : 상세한 내용 없이 클래스 형태만 가져와 생성해줍니다.

### Make Message Handler

```
php artisan xe_cli:make:messageHandler {plugin} {name} {--structure}
```

#### 예시

```
php artisan xe_cli:make:messageHandler xe_cli exam --structure
php artisan xe_cli:make:messageHandler xe_cli exam
```

#### 설명
- 옵션
    - --structure : 상세한 내용 없이 클래스 형태만 가져와 생성해줍니다.

### Make Validation Handler

```
php artisan xe_cli:make:validationHandler {plugin} {name} {--structure}
```

#### 예시

```
php artisan xe_cli:make:validationHandler xe_cli exam --structure
php artisan xe_cli:make:validationHandler xe_cli exam
```

#### 설명
- 옵션
    - --structure : 상세한 내용 없이 클래스 형태만 가져와 생성해줍니다.

</p>
</details>

---

<details>
<summary>모델 명령어</summary>

<p>

### Make Model

```
php artisan xe_cli:make:model {plugin} {name} {--migration}
```

#### 예시

```
php artisan xe_cli:make:model xe_cli exam --migration
php artisan xe_cli:make:model xe_cli exam
```

#### 설명
- 옵션
    - --migration : 모델에 대한 테이블 마이그레이션 파일 생성

</p>
</details>