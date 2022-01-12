<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use DOMDocument;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Skin\SkinHandler;
use Xpressengine\Widget\WidgetHandler;

/**
 * Class WidgetCodeCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class WidgetCodeCommand extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:widgetCode
                            {widget_id}
                            {skin_id}
                            {--inputs=}';

    /**
     * @var string
     */
    protected $description = 'Print Widget Code';

    /**
     * @var WidgetHandler
     */
    protected $widgetHandler;

    /**
     * @var SkinHandler
     */
    protected $skinHandler;

    /**
     * MoveMenuItemCommand __construct
     */
    public function __construct(
        WidgetHandler $widgetHandler,
        SkinHandler   $skinHandler
    )
    {
        $this->widgetHandler = $widgetHandler;
        $this->skinHandler = $skinHandler;

        parent::__construct();
    }

    /**
     * Move Menu Item Handle
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $widgetCode = $this->widgetHandler->generateCode(
            $this->widgetId(),
            $this->inputsOption()
        );

        $this->infoXmlFormat($widgetCode);
    }

    /**
     * Info Xml Format
     *
     * @param string $widgetCode
     * @return void
     */
    protected function infoXmlFormat(string $widgetCode)
    {
        /**
         * @TODO Dom Document 사용해 DOM ELEMENT 형태로 출력
         */
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $dom->loadXML($widgetCode);
        $this->info($dom->saveXML($dom->documentElement, LIBXML_NOEMPTYTAG));
    }

    /**
     * Get Inputs Option
     *
     * @return array
     * @throws Exception
     */
    protected function inputsOption(): array
    {
        $inputs = [];
        $defaultAttributes = $this->defaultAttributes();

        if (is_string($this->option('inputs')) === true) {
            /** @TODO JSON 형태가 올바르지 않은 경우에 대한 처리 방법을 가이드 문서로 기록합니다. */
            $inputs = json_decode($inputs, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $inputs = [];
            }
        }

        $attributes = array_merge(Arr::get($inputs, '@attributes', $defaultAttributes), [
            'id' => $this->widgetId(),
            'skin-id' => $this->skinId()
        ]);

        if (Arr::has($inputs, '@attributes') === true) {
            unset($inputs['@attributes']);
        }

        foreach ($attributes as $key => $value) {
            $inputs['@' . $key] = $value;
        }

        return $inputs;
    }

    /**
     * Get Widget's Id
     *
     * @return string
     * @throws Exception
     */
    protected function widgetId(): string
    {
        $widgetId = $this->argument('widget_id');

        if ($this->widgetHandler->getClassName($widgetId) === null) {
            throw new Exception('Not Found Widget ' . $widgetId);
        }

        return $widgetId;
    }

    /**
     * Get Skin's Id
     *
     * @return string
     * @throws Exception
     */
    protected function skinId(): string
    {
        $skinId = $this->argument('skin_id');

        if ($this->skinHandler->has($skinId) === false) {
            throw new Exception('Not Found Skin ' . $skinId);
        }

        return $skinId;
    }

    /**
     * Get Default Attributes
     *
     * @return string[]
     * @throws Exception
     */
    protected function defaultAttributes(): array
    {
        return [
            'id' => $this->widgetId(),
            'skin-id' => $this->skinId(),
            'activate' => 'activate',
            'title' => '',
        ];
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:print:widgetCode';
    }
}