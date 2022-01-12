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
