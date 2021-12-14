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
    {--force : 기존 파일 삭제 후 새롭게 생성}
```

#### 예시

```
php artisan xe_cli:make:migrationTable xe_cli exam --model
php artisan xe_cli:make:migrationTable xe_cli exam
php artisan xe_cli:make:migrationTable xe_cli exam --soft-delets
php artisan xe_cli:make:migrationTable xe_cli exam --model --soft-deletes
php artisan xe_cli:make:migrationTable xe_cli exam --force
```

<br>

### * Make Migration Resource

플러그인에서 제공하는 마이그레이션을 관리하는 마이그레이션 리소스 파일을 생성해줍니다.

```
xe_cli:make:migrationResource {plugin}
```
