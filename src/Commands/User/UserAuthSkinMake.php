<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\User;

use App\Console\Commands\SkinMake;
use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;
use SparkWeb\XePlugin\SparkCommand\Traits\RunChmodAws;
use Throwable;

final class UserAuthSkinMake extends SkinMake
{
    use RegisterArtisan, RunChmodAws;

    /**
     * 콘솔에 적히는 설명
     *
     * @var string
     */
    protected $signature = 'make:user-auth-skin
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
    protected $description = "Create a new user auth skin of XpressEngine";

    /**
     * 스킨 타겟
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'user/auth';
    }

    /**
     * 기준이 되는 코드 위치
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/auth';
    }

    /**
     * 코멘드를 통해서 새로운 스킨을 생성합니다.
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
        $this->renameStubFile($path . '/views/admin.blade.stub');
        $this->renameStubFile($path . '/views/agreement.blade.stub');
        $this->renameStubFile($path . '/views/confirm_email.blade.stub');
        $this->renameStubFile($path . '/views/login.blade.stub');
        $this->renameStubFile($path . '/views/password.blade.stub');
        $this->renameStubFile($path . '/views/pending_admin.blade.stub');
        $this->renameStubFile($path . '/views/pending_email.blade.stub');
        $this->renameStubFile($path . '/views/privacy.blade.stub');
        $this->renameStubFile($path . '/views/reset.blade.stub');
        $this->renameStubFile($path . '/views/terms.blade.stub');

        /** root/register */
        $this->renameStubFile($path . '/views/register/add-info.blade.stub');
        $this->renameStubFile($path . '/views/register/agreement.blade.stub');
        $this->renameStubFile($path . '/views/register/create.blade.stub');
        $this->renameStubFile($path . '/views/register/index.blade.stub');

        /** root/register/forms */
        $this->renameStubFile($path . '/views/register/forms/agreements.blade.stub');
        $this->renameStubFile($path . '/views/register/forms/confirm.blade.stub');
        $this->renameStubFile($path . '/views/register/forms/default.blade.stub');
        $this->renameStubFile($path . '/views/register/forms/dfields.blade.stub');
        $this->renameStubFile($path . '/views/register/forms/new_default.blade.stub');
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
