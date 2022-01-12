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