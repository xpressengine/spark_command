<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use XeDB;
use XeEditor;
use Exception;
use Illuminate\Console\Command;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class SetBoardContentsEditorConfigCommand
 *
 * Set Board Contents Editor Config Command
 * 보드 게시판 및 댓글의 Editor Config를 설정해주는 커맨드
 * Command => xe_cli:set:board_contents_editor_config
 *
 * @package XeHub\XePlugin\XeCli\Commands\Helper
 */
class SetBoardContentsEditorConfigCommand extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:set:board_contents_editor_config
        {editor_type}
        {--only-board}
        {--only-comment}
        {--instance_id=*}';

    // {editor_type} : 설정할 에디터 타입
    // {--only-board} : 게시판만 설정
    // {--only-comment} : 댓글만 설정
    // {--instance_id=*} : 특정 대상 지정 (복수 선택 가능)

    /**
     * @var string
     */
    protected $description = 'Set Board Contents Editor Config';

    /**
     * Set Board Contents Editor Handle
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        XeDB::beginTransaction();

        try {
            $targetInstanceIds = [];
            $editorType = $this->argument('editor_type');

            if (empty($this->option('instance_id')) === true) {
                $boardInstanceIds = [];
                $commentInstanceIds = [];
                if (empty($this->option('only-comment')) === true) {
                    $boardInstanceIds = \DB::table('config')
                        ->selectRaw("REPLACE(name, 'module/board@board.', '') as name")
                        ->where('name', 'like', 'module/board@board.%')
                        ->pluck('name')
                        ->toArray();
                }

                if (empty($this->option('only-board')) === true) {
                    $commentInstanceIds = \DB::table('config')
                        ->selectRaw("REPLACE(name, 'comment.', '') as name")
                        ->where('name', 'like', 'comment.%')
                        ->pluck('name')
                        ->toArray();
                }

                $targetInstanceIds = array_merge($boardInstanceIds, $commentInstanceIds);

            } else {
                $instanceIds = $this->option('instance_id');
                if (is_array($instanceIds) === true) {
                    $targetInstanceIds = $instanceIds;
                } else {
                    $targetInstanceIds[0] = $instanceIds;
                }
            }

            // set editor at instanceIds
            foreach ($targetInstanceIds as $instanceId) {
                XeEditor::setInstance($instanceId, $editorType);
                dump('set [instanceId:'.$instanceId.'] [editorType:'.$editorType.'] completed');
            }

            XeDB::commit();

        } catch (Exception $exception) {
            XeDB::rollback();
            throw $exception;
        }
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:set:board_contents_editor_config';
    }
}


