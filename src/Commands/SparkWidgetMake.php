<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Console\Commands\ComponentMakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Fluent;
use ReflectionException;
use Throwable;

final class SparkWidgetMake extends ComponentMakeCommand
{
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
    protected $description = 'Create a new widget of SparkWeb';

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
        $path = $this->getPath($this->option('path'));
        // $this->info('widget path: '. $path);

        $namespace = $this->getNamespace($path);
        // $this->info('namespace path: '. $namespace);

        $className = $this->getClassName();
        // $this->info('class name: '. $className);

        $file = $this->getClassFile($path, $className);
        // $this->info('class file: '. $file);

        $id = $this->getWidgetId();
        // $this->info('widget id: '. $id);

        $title = $this->getTitleInput();
        // $this->info($title);

        $description = $this->getDescriptionInput();
        // $this->info($description);

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

        // print and confirm the information of widget
        if ($this->confirmInfo($attr) === false) {
            return false;
        }

        $this->copyStubFile($plugin->getPath($path));

        try {
            $this->makeUsable($attr);
            $this->info('Generate the widget');

            // composer.json 파일 수정
            if ($this->registerComponent($plugin, $id, $namespace . '\\' . $className, $file, ['name' => $title, 'description' => $description]) === false) {
                throw new Exception('Writing to composer.json file was failed.');
            }

            $this->refresh($plugin);
        } catch (Exception $e) {
            $this->clean(sprintf('%s/%s', $plugin->getPath($path), $this->getStubFileName()));
            throw $e;
        } catch (Throwable $e) {
            $this->clean(sprintf('%s/%s', $plugin->getPath($path), $this->getStubFileName()));
            throw $e;
        }

        $this->confirmSkin($id);
        return true;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClassName()
    {
        return $this->option('class') ?: studly_case($this->getComponentName()) . 'Widget';
    }

    /**
     * Get widget id.    widget/<plugin_name>@<pure_id>
     *
     * @return array|string
     * @throws Exception
     */
    protected function getWidgetId()
    {
        $id = $this->option('id');
        $plugin = $this->getPlugin();

        if (is_null($id)) {
            $id = $plugin->getId() . '@' . strtolower($this->getComponentName());
        } else {
            if (strpos('widget/', $id) === 0) {
                $id = substr($id, 7);
            }

            if (strpos($id, '@') === false) {
                $id = $plugin->getId() . '@' . $id;
            }
        }

        $widget = app('xe.widget')->getClassName($id = 'widget/' . $id);

        if ($widget !== null) {
            throw new Exception("Widget [$id] already exists.");
        }

        return $id;
    }

    /**
     * @param string $widgetId
     * @return bool
     */
    protected function confirmSkin(string $widgetId)
    {
        while ($confirm = $this->ask('Do you want to add Skin? [yes|no]')) {
            if ($confirm !== 'yes') {
                return false;
            }

            $skinName = $this->ask('SkinName?', 'spark_default');

            $process = new Process(sprintf('php artisan make:skin %s %s %s', $this->getPluginName(), $skinName, $widgetId));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->info('Generate the skin');
            return true;
        }

        return false;
    }

    /**
     * Make file for plugin by stub.
     *
     * @param $attr
     * @return void
     * @throws Exception
     */
    protected function makeUsable($attr)
    {
        $this->makeWidgetClass($attr);
    }

    /**
     * Make widget class.
     *
     * @param $attr
     * @return void
     * @throws Exception
     */
    protected function makeWidgetClass($attr)
    {
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        $search = ['DummyNamespace', 'DummyClass'];
        $replace = [$attr['namespace'], $attr['className']];

        $this->info('widget make width class : ' . $path);

        $this->buildFile($path . '/widget.stub', $search, $replace, $plugin->getPath($attr['file']));
    }

    /**
     * @return array|string
     */
    protected function getPluginName()
    {
        return $this->argument('plugin');
    }

    /**
     * @return string
     */
    protected function getDefaultPath()
    {
        return 'Widgets';
    }

//    /**
//     * @return string
//     */
//    protected function getDefaultPath()
//    {
//        // return 'Widgets/' . studly_case($this->getComponentName());
//    }

    /**
     * Confirm information
     *
     * @param $attr
     * @return bool
     */
    protected function confirmInfo($attr)
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

        while ($confirm = $this->ask('Do you want to add Widget? [yes|no]')) {
            if ($confirm === 'yes') {
                return true;
            } else {
                return false;
            }
        }

        return null;
    }

    /**
     * @return array|string
     */
    protected function getComponentName()
    {
        return $this->argument('name');
    }

    /**
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/widget';
    }

    /**
     * 파일만 복사하기 위해서 생성.
     *
     * @return string
     */
    protected function getStubFileName()
    {
        return 'widget.stub';
    }

    /**
     * @param $path
     * @throws Exception
     */
    protected function copyStubFile($path)
    {
        $stubFile = $this->getStubFile($this->getStubFileName());
        $targetFile = sprintf('%s/%s', $path, $this->getStubFileName());

        if ($this->files->isDirectory($path) === false) {
            $this->files->makeDirectory($path);
        }

        if ($this->files->copy($stubFile, $targetFile) === false) {
            throw new \Exception("Unable to create file[$targetFile]. please check permission.");
        }
    }
}
