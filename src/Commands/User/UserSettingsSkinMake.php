<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\User;

use SparkWeb\XePlugin\SparkCommand\Traits\RunChmodAws;
use Throwable;
use App\Console\Commands\SkinMake;
use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;

final class UserSettingsSkinMake extends SkinMake
{
    use RegisterArtisan, RunChmodAws;

    /**3
     * 콘솔에서 사용하는 코멘드
     *
     * @var string
     */
    protected $signature = 'make:user-settings-skin
        {plugin : The plugin where the skin will be located}
        {name : The name of skin to create}

        {--id= : The identifier of skin. default "<plugin>@<name>"}
        {--path= : The path of skin. Enter the path to under the plugin. ex) SomeDir/SkinDir}
        {--class= : The class name of skin. default "<name>Skin"}';

    /**
     * 콘솔에 노출되는 설명
     *
     * @var string
     */
    protected $description = "Create a new user settings skin of XpressEngine";

    /**
     * 스킨 타겟
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'user/settings';
    }

    /**
     * 기준이 되는 코드 위치
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/settings';
    }

    /**
     * 코멘드를 통해서 새로운 유저 프로필 스킨을 생성합니다.
     *
     * @return bool|null
     * @throws Throwable
     */
    public function handle()
    {
        $result = parent::handle();
        $this->chmodAws();

        return $result;
    }

    /**
     * 베이스(Stub) 파일을 바탕으로 플로그인에서 사용할 파일을 생성합니다.
     *
     * @param $attr
     * @return void
     * @throws \Exception
     */
    protected function makeUsable($attr)
    {
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        $this->makeSkinClass($attr);

        /** Info */
        $this->renameStubFile($path . '/info.stub');

        /** root */
        $this->renameStubFile($path . '/views/edit.blade.stub');
        $this->renameStubFile($path . '/views/edit-field.blade.stub');
        $this->renameStubFile($path . '/views/index.blade.stub');
        $this->renameStubFile($path . '/views/show-field.blade.stub');
    }

    /**
     * rename Stub 파일
     *
     * @param $fileName
     */
    private function renameStubFile($fileName)
    {
        $changedFile = str_replace('stub', 'php', $fileName);
        rename($fileName, $changedFile);
    }
}
