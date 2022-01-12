### * Make Handler

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

### * Make Message Handler

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

### * Make Validation Handler

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