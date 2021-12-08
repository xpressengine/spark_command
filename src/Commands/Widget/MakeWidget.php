<?php

namespace XeHub\XePlugin\XeCli\Commands\Widget;

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
 * Make Widget Component Command
 * 위젯 컴포넌트 생성하는 커멘드
 * Command => xe_cli:make:widget
 *
 * @package XeHub\XePlugin\XeCli\Commands\Widget
 */
class MakeWidget extends ComponentMakeCommand
{
    use RegisterArtisan;

    protected $signature = 'xe_cli:make:widget
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
    protected $description = 'Create a New Widget';

    /**
     * The type of component
     *
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
     * @param Filesystem $files Filesystem instance
     * @param Operator $operator Operator instance
     * @param PluginHandler $handler PluginHandler instance
     * @param PluginProvider $provider PluginProvider instance
     * @param WidgetHandler $widgetHandler
     */
    public function __construct(
        Filesystem     $files,
        Operator       $operator,
        PluginHandler  $handler,
        PluginProvider $provider,
        WidgetHandler  $widgetHandler
    )
    {
        $this->widgetHandler = $widgetHandler;
        parent::__construct($files, $operator, $handler, $provider);
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
     * 위젯 생성 여부를 확인합니다.
     *
     * @throws ReflectionException
     * @throws FileNotFoundException
     * @return bool
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
     * 생성할 위젯 정보를 출력합니다.
     *
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
     * 위젯을 등록합니다.
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
     * 위젯 컴포넌트를 생성합니다,
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
     * 위젯 스킨을 생성합니다.
     *
     * @param string $widgetId
     * @return bool
     */
    protected function makeWidgetSkin(string $widgetId)
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
     * (widget/<plugin_name>@<pure_id>)
     *
     * @return array|string
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
     * Class Name
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
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginName(): string
    {
        return $this->argument('plugin');
    }

    /**
     * 위젯이 생성될 기본 디렉토리 위치
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getDefaultPath(): string
    {
        return 'Widgets/' . studly_case($this->argument('name'));
    }

    /**
     * Component's Name
     * (상속으로 재정의)
     *
     * @return array|string
     */
    protected function getComponentName(): string
    {
        return $this->argument('name');
    }

    /**
     * stub path
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        return __DIR__ . '/stubs';
    }
}
