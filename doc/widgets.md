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
