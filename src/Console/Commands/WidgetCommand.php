<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use App\Console\Commands\ComponentMakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use ReflectionException;
use Throwable;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Foundation\Operator;
use Xpressengine\Plugin\PluginHandler;
use Xpressengine\Plugin\PluginProvider;
use Xpressengine\Widget\WidgetHandler;

/**
 * Class MakeWidget
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class WidgetCommand extends ComponentMakeCommand implements CommandNameInterface
{
    use RegisterArtisan;

    protected $signature = 'xe_cli:make:widget
                            {plugin}
                            {name}
                            {--id=}
                            {--path=}
                            {--class=}';

    /**
     * @var string
     */
    protected $description = 'Create a New Widget';

    /**
     * @var string
     */
    protected $componentType = 'widget';

    /**
     * @var WidgetHandler
     */
    protected $widgetHandler;

    /**
     * Create a new component creator command instance.
     *
     * @param Filesystem $filesystem Filesystem instance
     * @param Operator $operator Operator instance
     * @param PluginHandler $handler PluginHandler instance
     * @param PluginProvider $provider PluginProvider instance
     * @param WidgetHandler $widgetHandler
     */
    public function __construct(
        Filesystem     $filesystem,
        Operator       $operator,
        PluginHandler  $handler,
        PluginProvider $provider,
        WidgetHandler  $widgetHandler
    )
    {
        $this->widgetHandler = $widgetHandler;
        parent::__construct($filesystem, $operator, $handler, $provider);
    }

    /**
     * @return false|null
     * @throws Throwable
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $widgetId = $this->getWidgetId();

        if ($this->widgetHandler->getClassName($widgetId) !== null) {
            $this->info(" Widget [$widgetId] Already Exists. ");
            $this->makeWidgetSkin($widgetId);

            return true;
        }

        if ($this->confirmCreateWidget() === false) {
            return false;
        }

        $plugin = $this->getPlugin();
        $path = $this->getPath($this->option('path'));

        $this->copyStubDirectory($plugin->getPath($path));

        try {
            $this->registerWidget();
        } catch (Exception $e) {
            $this->clean($path);
            throw $e;
        } catch (Throwable $e) {
            $this->clean($path);
            throw $e;
        }

        $this->makeWidgetSkin($widgetId);
        return true;
    }

    /**
     * @return bool
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function confirmCreateWidget(): bool
    {
        $this->showWidgetInfo();

        while ($confirm = $this->ask('Do you want to add widget? [yes|no]')) {
            if (strtolower($confirm) !== 'yes' && strtolower($confirm) !== 'no') {
                continue;
            }

            return strtolower($confirm) === 'yes';
        }

        return false;
    }

    /**
     * @return void
     * @throws FileNotFoundException
     * @throws ReflectionException
     * @throws Exception
     */
    protected function showWidgetInfo()
    {
        $plugin = $this->getPlugin();
        $widgetId = $this->getWidgetId();

        $path = $this->getPath($this->option('path'));
        $className = $this->getNamespace($path) . '\\' . $this->getClassName();
        $file = $this->getClassFile($path, $className);

        $title = $this->getTitleInput();
        $description = $this->getDescriptionInput();

        $this->info("[New {$this->componentType} Info]");
        $this->info(" Plugin:\t {$plugin->getId()}");
        $this->info(" Path:\t\t {$path}");
        $this->info(" Class File:\t {$file}");
        $this->info(" Class Name:\t {$className}");
        $this->info(" Id:\t\t {$widgetId}");
        $this->info(" Title:\t\t {$title}");
        $this->info(" Description:\t {$description}");
    }

    /**
     * Register Widget Component
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    protected function registerWidget()
    {
        $plugin = $this->getPlugin();
        $widgetId = $this->getWidgetId();

        $path = $this->getPath($this->option('path'));
        $namespace = $this->getNamespace($path);
        $className = $this->getClassName();
        $file = $this->getClassFile($path, $className);

        $title = $this->getTitleInput();
        $description = $this->getDescriptionInput();

        $attr = [
            'id' => $widgetId,
            'plugin' => $plugin,
            'title' => $title,
            'description' => $description,
            'path' => $path,
            'namespace' => $namespace,
            'className' => $className,
            'file' => $file,
        ];

        $this->makeWidget($attr);
        $this->info('Generate the widget');

        $info = [
            'name' => Arr::get($attr, 'title'),
            'description' => Arr::get($attr, 'description')
        ];

        $isResult = $this->registerComponent(
            $plugin,
            Arr::get($attr, 'id'),
            Arr::get($attr, 'namespace') . '\\' . Arr::get($attr, 'className'),
            Arr::get($attr, 'file'),
            $info
        );

        if ($isResult == false) {
            throw new Exception(
                'Writing to composer.json file was failed.'
            );
        }

        $this->refresh($plugin);
    }

    /**
     * Make Widget Component
     *
     * @param array $attr
     * @return void
     * @throws Exception
     */
    protected function makeWidget(array $attr)
    {
        $plugin = $this->getPlugin();
        $path = $plugin->getPath($attr['path']);

        $targetPath = $plugin->getId() . '/src/Widgets/' . studly_case($this->argument('name'));

        $replaceData = [
            '{{targetNamespace}}' => $attr['namespace'],
            '{{targetClass}}' => $attr['className'],
            '{{targetPath}}' => $targetPath
        ];

        $this->info('widget make width class : ' . $path);

        $this->buildFile(
            $path . '/widget.stub',
            array_keys($replaceData),
            $replaceData,
            $plugin->getPath($attr['file'])
        );

        $infoStubPath = $path . '/info.stub';

        rename(
            $infoStubPath,
            str_replace('stub', 'php', $infoStubPath)
        );

        $settingsBladeStubPath = $path . '/views/setting.blade.stub';

        rename(
            $settingsBladeStubPath,
            str_replace('stub', 'php', $settingsBladeStubPath)
        );
    }

    /**
     * Make Widget Skin
     *
     * @param string $widgetId
     * @return bool
     */
    protected function makeWidgetSkin(string $widgetId): bool
    {
        while ($confirm = $this->ask("Do you want to add widget's skin? [yes|no]")) {
            if (strtolower($confirm) === 'no') {
                break;
            }

            $pluginNam = $this->getPluginName();
            $skinName = $this->askWidgetSkinName();
            $widgetName = studly_case($this->getComponentName()) . 'Widget';
            $path = 'src/Skins/' . $widgetName . '/' . studly_case($skinName);

            $this->call('make:skin', [
                'plugin' => $pluginNam,
                'name' => $skinName,
                'target' => $widgetId,
                '--path' => $path
            ]);
        }

        return strtolower($confirm) === 'yes';
    }

    /**
     * Get Widget Component's Id
     *
     * @return string
     * @throws Exception
     */
    protected function getWidgetId(): string
    {
        $widgetId = $this->option('id');
        $plugin = $this->getPlugin();

        if (is_null($widgetId) === true) {
            $widgetId = $plugin->getId() . '@' . strtolower($this->getComponentName());
        } else {
            if (strpos('widget/', $widgetId) === 0) {
                $widgetId = substr($widgetId, 7);
            }

            if (strpos($widgetId, '@') === false) {
                $widgetId = $plugin->getId() . '@' . $widgetId;
            }
        }

        return 'widget/' . $widgetId;
    }

    /**
     * Ask Widget's Skin Name
     *
     * @return string
     */
    protected function askWidgetSkinName(): string
    {
        return $this->ask('SkinName?', 'xehub_default');
    }

    /**
     * Get Class's Name
     *
     * @return string
     */
    protected function getClassName(): string
    {
        $class = $this->option('class');

        if ($class !== null) {
            return $class;
        }

        return studly_case($this->getComponentName()) . 'Widget';
    }

    /**
     * Plugin's Name
     *
     * @return string
     */
    protected function getPluginName(): string
    {
        return $this->argument('plugin');
    }

    /**
     * 위젯이 생성될 기본 디렉토리 위치
     *
     * @return string
     */
    protected function getDefaultPath(): string
    {
        return 'Widgets/' . studly_case($this->argument('name'));
    }

    /**
     * Component's Name
     *
     * @return array|string
     */
    protected function getComponentName(): string
    {
        return $this->argument('name');
    }

    /**
     * Get Stub's Path
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        return __DIR__ . '/../stubs/widget';
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:widget';
    }
}
