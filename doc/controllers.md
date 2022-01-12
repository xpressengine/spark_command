### * Make Controller

특정 도메인 (name)에 대한 Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:controller 
    {plugin}
    {name} 
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

### * Make BackOffice Controller

특정 도메인 (name)에 대한 BackOffice Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:backOfficeController 
    {plugin}
    {name}
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

### * Make Client Controller

특정 도메인 (name)에 대한 Client Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:clientController {plugin} {name}
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

### * Make API Controller

특정 도메인 (name)에 대한 API Controller 파일을 생성해줍니다.

```
php artisan xe_cli:make:apiController 
    {plugin} 
    {name}
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