<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\Error;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;
use Throwable;
use App\Console\Commands\SkinMake;
use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;
use SparkWeb\XePlugin\SparkCommand\Traits\RunChmodAws;

final class ErrorSkinMake extends SkinMake
{
    use RegisterArtisan, RunChmodAws;

    /**
     * 콘솔에 적히는 설명
     *
     * @var string
     */
    protected $signature = 'make:error-skin
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
     * 기준이 되는 코드 위치
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs';
    }

    /**
     * 생성되는 코드 위치
     *
     * @return string
     */
    protected function getDefaultPath()
    {
        return '../views/error/' . Str::camel($this->argument('name'));
    }

    /**
     * 스킨 타겟
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return '';
    }

    /**
     * 코멘드를 통해서 새로운 스킨을 생성합니다.
     *
     * @return bool|null
     * @throws Throwable
     */
    public function handle()
    {
        // get plugin info
        $plugin = $this->getPlugin();

        // get skin info
        $path = $this->getPath($this->option('path'));
        $id = $this->getSkinId();

        $attr = new Fluent(compact('plugin', 'path', 'id'));
        $this->copyStubDirectory($plugin->getPath($path));

        try {

            $this->makeUsable($attr);
            $this->info('Generate the skin');
        }

        catch (\Exception $e) {
            $this->clean($path);
            throw $e;
        }

        catch (\Throwable $e) {
            $this->clean($path);
            throw $e;
        }

        $this->info("Skin is created successfully.");
        $this->chmodAws();
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

        /** root */
        $this->renameStubFile($path . '/401.blade.stub');
        $this->renameStubFile($path . '/403.blade.stub');
        $this->renameStubFile($path . '/404.blade.stub');
        $this->renameStubFile($path . '/500.blade.stub');
        $this->renameStubFile($path . '/503.blade.stub');
        $this->renameStubFile($path . '/_frame.blade.stub');
        $this->renameStubFile($path . '/error.blade.stub');
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
