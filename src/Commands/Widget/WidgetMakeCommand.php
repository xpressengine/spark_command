<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\Widget;

use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;
use SparkWeb\XePlugin\SparkCommand\Traits\RunChmodAws;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Console\Commands\ComponentMakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Fluent;
use ReflectionException;
use Throwable;

final class WidgetMakeCommand extends ComponentMakeCommand
{
    use RegisterArtisan, RunChmodAws;

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

        if ($widget !== null) {
            $this->error(" Widget [$id] already exists. ");
        }

        else
        {
            $path           = $this->getPath($this->option('path'));
            $namespace      = $this->getNamespace($path);
            $className      = $this->getClassName();
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

            $this->copyStubFile($plugin->getPath($path));

            try {
                $this->makeUsable($attr);
                $this->info('Generate the widget');

                $className  = $namespace . '\\' . $className;
                $info       = ['name' => $title, 'description' => $description];

                if ($this->registerComponent($plugin, $id, $className, $file, $info) === false) {
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

            $this->chmod();
        }

        $this->confirmSkin($id);
        return true;
    }

    /**
     * @return array|string
     */
    protected function getComponentName()
    {
        return $this->argument('name');
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

    /**
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/Stubs/Widget';
    }

    /**
     * @return string
     */
    protected function getStubFileName()
    {
        return 'widget.stub';
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
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        $search = ['DummyNamespace', 'DummyClass'];
        $replace = [$attr['namespace'], $attr['className']];

        $this->info('widget make width class : ' . $path);
        $this->buildFile(sprintf('%s/%s', $path, 'widget.stub'), $search, $replace, $plugin->getPath($attr['file']));
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

    /**
     * Get widget id. widget/<plugin_name>@<pure_id>
     *
     * @return array|string
     * @throws Exception
     */
    protected function getWidgetId()
    {
        $id     = $this->option('id');
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

        return 'widget/' . $id;
    }

    /**
     * Confirm information
     *
     * @param $attr
     * @return bool
     */
    protected function confirmInfo($attr)
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
     * @param string $widgetId
     * @return bool
     */
    protected function confirmSkin(string $widgetId)
    {
        while ($confirm = $this->ask("Do you want to add widget's skin? [yes|no]"))
        {
            if (strtolower($confirm) === 'no') {
                break;
            }

            $skinName = $this->ask('SkinName?', 'spark_default');
            $command = sprintf('php artisan make:skin %s %s %s', $this->getPluginName(), $skinName, $widgetId);

            $process = new Process([$command]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->info('Generate the skin');
            $this->chmodAws();

            continue;
        }

        return strtolower($confirm) === 'yes';
    }
}
