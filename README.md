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

<br>
XE CLI Repository가 복제되었다면 XpressEngine3이(가) 설치된 루트로 이동해 아래 명령어를 실행합니다.

```
php artisan plugin:private_install xe_cli
```

<br>
이후 아래 명령어로 XE CLI 플러그인을 활성화시켜 주세요.

```
php artisan plugin:private_install xe_cli
```

</p>
</details>

---

<br>

## 컨트롤러 명령어

<details>
<summary>컨트롤러 명령어</summary>

<p>

###  * Make Controller

특정 도메인 (name)에 대한 Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:controller 
    {plugin : 새로운 컨트롤러를 생성할 플러그인 이름}
    {name : 도메인 이름} 
    {--resource : Laravel Resource 형태에 맞춰 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### Command 예시

```
php artisan xe_cli:make:controller xe_cli exam   
php artisan xe_cli:make:controller xe_cli exam --resource  
php artisan xe_cli:make:controller xe_cli exam --force    
php artisan xe_cli:make:controller xe_cli exam --force --resource    
```

<br>

###  * Make BackOffice Controller

특정 도메인 (name)에 대한 BackOffice Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:backOfficeController 
    {plugin : 새로운 컨트롤러를 생성할 플러그인 이름}
    {name : 도메인 이름} 
    {--complete : 완성된 형태로 생성 (라우트/모델/핸들러 등 관련 파일을 같이 생성됩니다.)}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### Command 예시

```
php artisan xe_cli:make:backOfficeController xe_cli exam
php artisan xe_cli:make:backOfficeController xe_cli exam --complete
php artisan xe_cli:make:backOfficeController xe_cli exam --force
php artisan xe_cli:make:backOfficeController xe_cli exam --force --complete
```

<br>

###  * Make Client Controller

특정 도메인 (name)에 대한 Client Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:clientController 
    {plugin : 새로운 컨트롤러를 생성할 플러그인 이름}
    {name : 도메인 이름} 
    {--resource : Laravel Resource 형태에 맞춰 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:clientController xe_cli exam
php artisan xe_cli:make:clientController xe_cli exam --resource
php artisan xe_cli:make:clientController xe_cli exam --force
php artisan xe_cli:make:clientController xe_cli exam --resource --force
```

<br>

###  * Make API Controller

특정 도메인 (name)에 대한 API Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:apiController 
    {plugin : 새로운 컨트롤러를 생성할 플러그인 이름}
    {name : 도메인 이름} 
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:apiController xe_cli exam
php artisan xe_cli:make:apiController xe_cli exam --complete
php artisan xe_cli:make:apiController xe_cli exam --force
php artisan xe_cli:make:apiController xe_cli exam --complete --force
```

</p>
</details>

---

<br>

## 핸들러 명령어

<details>
<summary>핸들러 명령어</summary>

<p>

###  * Make Handler

특정 도메인 (name)에 대한 Handler 파일을 생성해줍니다.

```
php artisan xe_cli:make:handler 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성 (모델/마이그레이션 등 관련 파일을 같이 생성됩니다.)}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:handler xe_cli exam
php artisan xe_cli:make:handler xe_cli exam --complete
php artisan xe_cli:make:handler xe_cli exam --force
php artisan xe_cli:make:handler xe_cli exam --complete --force
```

<br>

###  * Make Message Handler

특정 도메인 (name)에 대한 Message Handler 파일을 생성해줍니다.

```
php artisan xe_cli:make:messageHandler 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:messageHandler xe_cli exam
php artisan xe_cli:make:messageHandler xe_cli exam --complete
php artisan xe_cli:make:messageHandler xe_cli exam --force
php artisan xe_cli:make:messageHandler xe_cli exam --complete --force
```

<br>

###  * Make Validation Handler

특정 도메인 (name)에 대한 Validation Handler 파일을 생성해줍니다.

```
php artisan xe_cli:make:validationHandler 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:validationHandler xe_cli exam
php artisan xe_cli:make:validationHandler xe_cli exam --complete
php artisan xe_cli:make:validationHandler xe_cli exam --force
php artisan xe_cli:make:validationHandler xe_cli exam --complete --force
```

</p>
</details>

---

<br>

## 모델 명령어

<details>
<summary>모델 명령어</summary>
<p>

### * Make Model

특정 도메인 (name)에 대한 Model 파일을 생성해줍니다.

```
php artisan xe_cli:make:model 
    {plugin}
    {name} 
    {--migration : 테이블 마이그레이션 파일 생성}
    {--table= : 모델의 테이블 이름 설정}
    {--pk=id : 모델에서 사용할 프라이머리 키 설정}
    {--soft-deletes : soft-deletes 기능을 사용할 수 있도록 설정}
    {--incrementing : incrementing 기능을 사용할 수 있도록 설정}
    {--timestamps : timestamps 기능을 사용할 수 있도록 설정}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:model xe_cli exam
php artisan xe_cli:make:model xe_cli exam --migration
php artisan xe_cli:make:model xe_cli exam --table=xe_hub_table
php artisan xe_cli:make:model xe_cli exam --table=xe_hub_table --soft-deletes
php artisan xe_cli:make:model xe_cli exam --soft-deletes
php artisan xe_cli:make:model xe_cli exam --soft-deletes --force
php artisan xe_cli:make:model xe_cli exam --soft-deletes --timestamps --force
php artisan xe_cli:make:model xe_cli exam --timestamps --force
```

</p>
</details>

---

<br>

## 마이그레이션 명령어

<details>
<summary>마이그레이션 명령어</summary>
<p>

### * Session - Database Table 마이그레이션

세션을 데이터베이스에서 관리하기 위해 관련된 Table, Config 를 추가해줍니다.

```
php artisan xe_cli:migrate:sessionDatabase
```

<br>

### * Queue - Database Table 마이그레이션

큐를 데이터베이스에서 관리하기 위해 관련된 Table, Config 를 추가해줍니다.

```
php artisan xe_cli:migrate:queueDatabase
```

<br>

### * Make Migration Table

테이블에 대한 마이그레이션 파일을 생성해줍니다.

```
xe_cli:make:migrationTable {plugin} {name} 
    {--pk=id : 테이블에서 사용할 프라이머리 키 설정}
    {--model : 마이그레이션에 대한 모델 파일을 생성}
    {--soft-deletes : soft-deletes 기능을 사용할 수 있도록 설정}
    {--incrementing : incrementing 기능을 사용할 수 있도록 설정}
    {--timestamps : timestamps 기능을 사용할 수 있도록 설정}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:migrationTable xe_cli exam --model
php artisan xe_cli:make:migrationTable xe_cli exam
php artisan xe_cli:make:migrationTable xe_cli exam --soft-delets
php artisan xe_cli:make:migrationTable xe_cli exam --model --soft-deletes
php artisan xe_cli:make:migrationTable xe_cli exam --force --timestamps
php artisan xe_cli:make:migrationTable xe_cli exam --force
php artisan xe_cli:make:migrationTable xe_cli exam --timestamps
```

<br>

### * Make Migration Resource

플러그인에서 제공하는 마이그레이션을 관리하는 마이그레이션 리소스 파일을 생성해줍니다.

```
xe_cli:make:migrationResource {plugin}
```

</p>
</details>

---

<br>

## 스킨 명령어

<details>
<summary>스킨 명령어</summary>
<p>

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

</p>
</details>

---

<br>

## 위젯 명령어

<details>
<summary>위젯 명령어</summary>
<p>

### * Make Widget

플러그인에 새로운 위젯 컴포넌트를 생성해줍니다.

```
php artisan xe_cli:make:widget 
    {plugin_name : 위젯을 생성할 플러그인 이름}
    {widget_name : 위젯 이름}
```

#### 예시

```
php artisan xe_cli:make:widget xe_cli exam
php artisan xe_cli:make:widget xe_cli test
```

### * Widget Code

입력된 값에 맞춰서 위젯 코드를 생성해줍니다.

json 값에 addslashes() 적용하기 위한 사이트.  
https://www.w3schools.com/php/phptryit.asp?filename=tryphp_func_string_addslashes

```
php artisan xe_cli:widgetCode
    {widget_id : 위젯 컴포넌트 아이디}
    {skin_id : 위젯에 대한 스킨 컴포넌트 아이디}
    {--inputs= : json에 addslashes가 적용된 형태로 입력}
```

#### 예시

```
php artisan xe_cli:widgetCode widget/xpressengine@contentInfo  widget/xpressengine@contentInfo/skin/xpressengine@default
php artisan xe_cli:widgetCode widget/news_client@news widget/news_client@news/skin/news_client@default
```


</p>
</details>

---

<br>

## 유틸 명령어

<details>
<summary>유틸 명령어</summary>
<p>

### * Move MenuItem

대상이 되는 메뉴 아이템을 특정 메뉴로 이동시킵니다.

```
php artisan xe_cli:move:menuItem 
    {menu}
    {menuItem*}
    {--position=}
```

<br>

### * Set Menu Item's Order

메뉴 아이템의 순서를 변경합니다.

```
php artisan xe_cli:setPosition:menuItem 
    {menuItem}
    {position}
```

<br>

### * Set board contents editor config

보드 게시판 및 댓글 전체 혹은 특정부분만 Editor Config를 설정합니다

```
php artisan xe_cli:set:board_contents_editor_config
    {editor_type}
    {--only-board}
    {--only-comment}
    {--instance_id=*}
    
// {editor_type} : 설정할 에디터 타입 (예: editor/ckeditor@ckEditor)
// {--only-board} : 게시판만 설정
// {--only-comment} : 댓글만 설정
// {--instance_id=*} : 특정 대상 지정 (복수선택가능)
```

</p>
</details>
