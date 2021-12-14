### * Make BackOffice Index View

특정 도메인 (name)에 대한 BackOffice Index View 파일을 생성해줍니다.

```
php artisan xe_cli:make:backOfficeIndexView 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:backOfficeIndexView xe_cli exam
php artisan xe_cli:make:backOfficeIndexView xe_cli exam --complete
php artisan xe_cli:make:backOfficeIndexView xe_cli exam --force
php artisan xe_cli:make:backOfficeIndexView xe_cli exam --force --complete
```

<br>

### * Make BackOffice Show View

특정 도메인 (name)에 대한 BackOffice Show View 파일을 생성해줍니다.

```
php artisan xe_cli:make:backOfficeShowView 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:backOfficeShowView xe_cli exam
php artisan xe_cli:make:backOfficeShowView xe_cli exam --complete
php artisan xe_cli:make:backOfficeShowView xe_cli exam --force
php artisan xe_cli:make:backOfficeShowView xe_cli exam --force --complete
```

<br>

### * Make BackOffice Create View

```
php artisan xe_cli:make:backOfficeCreateView 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:backOfficeCreateView xe_cli exam
php artisan xe_cli:make:backOfficeCreateView xe_cli exam --complete 
php artisan xe_cli:make:backOfficeCreateView xe_cli exam --force
php artisan xe_cli:make:backOfficeCreateView xe_cli exam --force --complete
```

<br>

### * Make BackOffice Edit View

```
php artisan xe_cli:make:backOfficeEditView 
    {plugin}
    {name}
    {--complete : 완성된 형태로 생성}
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:backOfficeEditView xe_cli exam
php artisan xe_cli:make:backOfficeEditView xe_cli exam --complete 
php artisan xe_cli:make:backOfficeEditView xe_cli exam --force
php artisan xe_cli:make:backOfficeEditView xe_cli exam --force --complete
```