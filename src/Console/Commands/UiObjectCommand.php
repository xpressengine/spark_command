<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use App\Console\Commands\ComponentMakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use ReflectionException;
use RuntimeException;
use Throwable;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Foundation\Operator;
use Xpressengine\Plugin\PluginHandler;
use Xpressengine\Plugin\PluginProvider;
use Xpressengine\UIObject\UIObjectHandler;

/**
 * Class UiObjectCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class UiObjectCommand extends ComponentMakeCommand implements CommandNameInterface
{
    use RegisterArtisan;

    protected $signature = 'xe_cli:make:ui_object
                            {plugin}
                            {name}
                            {--id=}
                            {--path=}
                            {--class=}
                            {--skip}';

    /**
     * @var string
     */
    protected $description = 'Create a New UI Object';

    /**
     * @var string
     */
    protected $componentType = 'uiobject';

    /**
     * @var UIObjectHandler
     */
    protected $uiObjectHandler;

    /**
     * Create a new component creator command instance.
     *
     * @param  Filesystem  $filesystem  Filesystem instance
     * @param  Operator  $operator  Operator instance
     * @param  PluginHandler  $handler  PluginHandler instance
     * @param  PluginProvider  $provider  PluginProvider instance
     * @param  UIObjectHandler  $uiObjectHandler
     */
    public function __construct(
        Filesystem     $filesystem,
        Operator       $operator,
        PluginHandler  $handler,
        PluginProvider $provider,
        UIObjectHandler $uiObjectHandler
    )
    {
        $this->uiObjectHandler = $uiObjectHandler;
        parent::__construct($filesystem, $operator, $handler, $provider);
    }

    /**
     * @return false|null
     * @throws Throwable
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $uiObjectId = $this->getUiObjectId();

        if ($this->uiObjectHandler->get($uiObjectId) !== null) {
            $this->info(" UI Object [$uiObjectId] Already Exists. ");
            return true;
        }

        if (!$this->option('skip') && $this->confirmCreateUiObject() === false) {
            return false;
        }

        $plugin = $this->getPlugin();
        $path = $this->getPath($this->option('path'));

        $this->copyStubDirectory($plugin->getPath($path));

        try {
            $this->registerUiObject();
        } catch (Exception $e) {
            $this->clean($path);
            throw $e;
        } catch (Throwable $e) {
            $this->clean($path);
            throw $e;
        }

        return true;
    }

    /**
     * @return bool
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function confirmCreateUiObject(): bool
    {
        $this->showUiObjectInfo();

        while ($confirm = $this->ask('Do you want to add Ui Object? [yes|no]')) {
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
    protected function showUiObjectInfo()
    {
        $plugin = $this->getPlugin();
        $uiObjectId = $this->getUiObjectId();

        $path = $this->getPath($this->option('path'));
        $className = $this->getNamespace($path) . '\\' . $this->getClassName();
        $file = $this->getClassFile($path, $this->getClassName());

        $title = $this->getTitleInput();
        $description = $this->getDescriptionInput();

        $this->info("[New $this->componentType Info]");
        $this->info(" Plugin:\t {$plugin->getId()}");
        $this->info(" Path:\t\t $path");
        $this->info(" Class File:\t $file");
        $this->info(" Class Name:\t $className");
        $this->info(" Id:\t\t $uiObjectId");
        $this->info(" Title:\t\t $title");
        $this->info(" Description:\t $description");
    }

    /**
     * Register Ui Object Component
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    protected function registerUiObject()
    {
        $plugin = $this->getPlugin();
        $uiObjectId = $this->getUiObjectId();

        $path = $this->getPath($this->option('path'));
        $namespace = $this->getNamespace($path);
        $className = $this->getClassName();
        $file = $this->getClassFile($path, $className);

        $title = $this->getTitleInput();
        $description = $this->getDescriptionInput();

        $attr = [
            'id' => $uiObjectId,
            'plugin' => $plugin,
            'title' => $title,
            'description' => $description,
            'path' => $path,
            'namespace' => $namespace,
            'className' => $className,
            'file' => $file,
        ];

        $this->makeUiObject($attr);
        $this->info('Generate the ui object');

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

        if ($isResult === false) {
            throw new RuntimeException('Writing to composer.json file was failed.');
        }

        $this->refresh($plugin);
    }

    /**
     * Make Ui Object Component
     *
     * @param array $attr
     * @return void
     * @throws Exception
     */
    protected function makeUiObject(array $attr)
    {
        $plugin = $this->getPlugin();
        $path = $plugin->getPath($attr['path']);

        $targetPath = $plugin->getId() . '/src/Components/UiObjects/' . studly_case($this->argument('name'));

        $replaceData = [
            '{{pluginId}}' => $plugin->getId(),
            '{{targetNamespace}}' => $attr['namespace'],
            '{{targetClass}}' => $attr['className'],
            '{{targetPath}}' => $targetPath,
            '{{componentName}}' => $this->getComponentName()
        ];

        $this->info('ui object make width class : ' . $path);

        $this->buildFile(
            $path . '/uiobject.stub',
            array_keys($replaceData),
            $replaceData,
            $plugin->getPath($attr['file'])
        );

        $indexBladeStubPath = $path . '/views/index.blade.stub';

        rename(
            $indexBladeStubPath,
            str_replace('stub', 'php', $indexBladeStubPath)
        );
    }

    /**
     * Get Ui Object's Id
     *
     * @return string
     * @throws Exception
     */
    protected function getUiObjectId(): string
    {
        $uiObjectId = $this->option('id');
        $plugin = $this->getPlugin();

        if (is_null($uiObjectId) === true) {
            $uiObjectId = $plugin->getId() . '@' . strtolower($this->getComponentName());
        } else {
            if (strpos('uiobject/', $uiObjectId) === 0) {
                $uiObjectId = substr($uiObjectId, 7);
            }

            if (strpos($uiObjectId, '@') === false) {
                $uiObjectId = $plugin->getId() . '@' . $uiObjectId;
            }
        }

        return 'uiobject/' . $uiObjectId;
    }

    /**
     * Get Class's Name
     *
     * @return string
     */
    protected function getClassName(): string
    {
        $class = $this->option('class');

        return $class ?? (studly_case($this->getComponentName()).'UiObject');
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
        return 'Components/UiObjects/' . studly_case($this->argument('name'));
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
        return __DIR__ . '/../stubs/uiobject';
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:ui_object';
    }
}
