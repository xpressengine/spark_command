<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\Widget;

use App\Console\Commands\ComponentMakeCommand;
use Illuminate\Support\Fluent;
use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use Exception;
use Throwable;
use Xpressengine\Plugin\PluginEntity;

final class WidgetMake extends ComponentMakeCommand
{
    use RegisterArtisan;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:widget
        {plugin : The plugin where the widget will be located}
        {name : The name of widget to create}

        {--id= : The identifier of widget. default "<plugin>@<name>"}
        {--path= : The path of widget. Enter the path to under the plugin. ex) SomeDir/WidgetDir}
        {--class= : The class name of widget. default "<name>Widget"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new widget of Sparkweb';

    /**
     * The type of component
     *
     * @var string
     */
    protected $componentType = 'widget';

    /**
     * @return false|null
     * @throws Throwable
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function handle()
    {
        // Get Plugin Info
        $plugin = $this->getPlugin();

        // Get Widget Info
        $id             = $this->getWidgetId();
        $widget         = app('xe.widget')->getClassName($id);

        if ($widget !== null)
        {
            $this->error(" Widget [$id] already exists. ");
            $this->confirmSkin($id);
            return false;
        }

        $path           = $this->getPath($this->option('path'));
        $namespace      = $this->getNamespace($path);
        $className      = $this->option('class') ?: studly_case($this->getComponentName()) . 'Widget';
        $file           = $this->getClassFile($path, $className);

        $title          = $this->getTitleInput();
        $description    = $this->getDescriptionInput();

        $attr = new Fluent(compact(
            'plugin',
            'path',
            'namespace',
            'className',
            'file',
            'id',
            'title',
            'description'
        ));

        if ($this->confirmInfo($attr) === false) {
            return false;
        }

        $this->copyStubDirectory($plugin->getPath($path));

        try {
            $this->makeUsable($attr);
            $this->info('Generate the widget');

            $className  = $namespace . '\\' . $className;
            $info       = ['name' => $title, 'description' => $description];

            if ($this->registerComponent($plugin, $id, $className, $file, $info) === false) {
                throw new Exception('Writing to composer.json file was failed.');
            }

            $this->refresh($plugin);
        }

        catch (Exception $e) {
            $this->clean($path);
            throw $e;
        }

        catch (Throwable $e) {
            $this->clean($path);
            throw $e;
        }

        $this->confirmSkin($id);

        return true;
    }

    /**
     * 생성할 위젯 정보를 보고 확인합니다.
     *
     * @param $attr
     * @return bool
     */
    private function confirmInfo($attr)
    {
        $this->showInfo($attr);

        while ($confirm = $this->ask('Do you want to add widget? [yes|no]'))
        {
            if (strtolower($confirm) !== 'yes' && strtolower($confirm) !== 'no') {
                continue;
            }

            return strtolower($confirm) === 'yes';
        }

        return false;
    }

    /**
     * 생성할 위젯 정보를 보여줍니다.
     *
     * @param $attr
     */
    protected function showInfo($attr)
    {
        $this->info(
            sprintf(
                "[New %s info]
  plugin:\t %s
  path:\t\t %s
  class file:\t %s
  class name:\t %s
  id:\t\t %s
  title:\t %s
  description:\t %s",
                $this->componentType,
                $attr['plugin']->getId(),
                $attr['path'],
                $attr['file'],
                $attr['namespace'] . '\\' . $attr['className'],
                $attr['id'],
                $attr['title'],
                $attr['description']
            )
        );
    }

    /**
     * 생성할 컴포넌트 이름
     *
     * @return array|string
     */
    protected function getComponentName()
    {
        return $this->argument('name');
    }

    /**
     * 대상이 되는 플러그인 이름
     *
     * @return array|string
     */
    protected function getPluginName()
    {
        return $this->argument('plugin');
    }

    /**
     * stub 디렉토리 위치
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs';
    }

    /**
     * 위젯이 생성될 기본 디렉토리 위치
     *
     * @return string
     */
    protected function getDefaultPath()
    {
        return 'Widgets/' . studly_case($this->argument('name'));
    }

    /**
     * 위젯 아이디 반환. (widget/<plugin_name>@<pure_id>)
     *
     * @return array|string
     * @throws Exception
     */
    private function getWidgetId()
    {
        $id = $this->option('id');
        $plugin = $this->getPlugin();

        if (is_null($id)) {
            $id = $plugin->getId() . '@' . strtolower($this->getComponentName());
        }

        else
        {
            if (strpos('widget/', $id) === 0) {
                $id = substr($id, 7);
            }

            if (strpos($id, '@') === false) {
                $id = $plugin->getId() . '@' . $id;
            }
        }

        return 'widget/' . $id;
    }

    /**
     * 위젯 컴포넌트를 생성합니다,
     *
     * @param $attr
     * @return void
     * @throws Exception
     */
    protected function makeUsable($attr)
    {
        /** @var PluginEntity $plugin */
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        $dummyPath = sprintf('%s/src/Widgets/%s', $plugin->getId(), studly_case($this->argument('name')));

        $search = ['DummyNamespace', 'DummyClass', 'DummyPath'];
        $replace = [$attr['namespace'], $attr['className'], $dummyPath];

        $this->info('widget make width class : ' . $path);
        $this->buildFile(sprintf('%s/%s', $path, 'widget.stub'), $search, $replace, $plugin->getPath($attr['file']));

        $this->renameStubFile(sprintf('%s/%s', $path, 'info.stub'));
        $this->renameStubFile(sprintf('%s/views/%s', $path, 'setting.blade.stub'));
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

    /**
     * 스킨 생성할지 확인합니다.
     *
     * @param string $target
     * @return bool
     */
    protected function confirmSkin(string $target)
    {
        while ($confirm = $this->ask("Do you want to add widget's skin? [yes|no]"))
        {
            if (strtolower($confirm) === 'no') {
                break;
            }

            $plugin = $this->getPluginName();
            $name = $this->ask('SkinName?', 'spark_default');
            $widgetName = studly_case($this->getComponentName()) . 'Widget';
            $path = sprintf('src/Skins/%s/%s', $widgetName, studly_case($name));

            $arguments = compact('plugin', 'name', 'target');
            $arguments['--path'] = $path;

            $this->call('make:skin', $arguments);
            continue;
        }

        return strtolower($confirm) === 'yes';
    }
}
