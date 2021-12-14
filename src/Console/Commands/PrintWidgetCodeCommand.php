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
 * Class PrintWidgetCodeCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class PrintWidgetCodeCommand extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:print:widgetCode
                            {widgetId}
                            {skinId}
                            {--inputs=}
                            {--snippet}';

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
        $widgetId = $this->getWidgetId();
        if ($this->widgetHandler->getClassName($widgetId) === null) {
            throw new Exception('Not Found Widget ' . $widgetId);
        }

        $skinId = $this->getSkinId();
        if ($this->skinHandler->has($skinId) === null) {
            throw new Exception('Not Found Skin ' . $skinId);
        }

        $this->infoWidgetCode($widgetId, $skinId);
    }

    /**
     * Info Inputs Widget Code
     *
     * @throws Exception
     */
    protected function infoWidgetCode(string $widgetId, string $skinId)
    {
        $inputs = $this->getInputs();

        $defaultInputAttribute = $this->getDefaultInputAttribute();
        $inputAttribute = Arr::get($inputs, '@attributes', $defaultInputAttribute);

        $inputAttribute = array_merge($inputAttribute, [
            'id' => $widgetId,
            'skin-id' => $skinId
        ]);

        if (Arr::has($inputs, '@attributes') === true) {
            unset($inputs['@attributes']);
        }

        foreach ($inputAttribute as $key => $value) {
            $inputs['@' . $key] = $value;
        }

        $widgetCode = $this->widgetHandler->generateCode($widgetId, $inputs);
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

        $this->info($dom->saveXML($dom->documentElement));
    }

    /**
     * Get Default Input Attributes
     *
     * @return array
     */
    protected function getDefaultInputAttribute(): array
    {
        return [
            'id' => $this->getWidgetId(),
            'skin-id' => $this->getSkinId(),
            'activate' => 'activate',
            'title' => '',
        ];
    }

    /**
     * Get Inputs
     *
     * @return array
     * @throws Exception
     */
    protected function getInputs(): array
    {
        $inputs = $this->option('inputs');

        if (is_null($inputs) === true) {
            return [];
        }

        /** @TODO JSON 형태가 올바르지 않은 경우에 대한 처리 방법을 가이드 문서로 기록합니다. */
        $decodedInputs = json_decode($inputs, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error(json_last_error_msg());
            return [];
        }

        return $decodedInputs;
    }

    /**
     * Get Widget's Id
     *
     * @return string
     */
    protected function getWidgetId(): string
    {
        return $this->argument('widgetId');
    }

    /**
     * Get Skin's Id
     *
     * @return string
     */
    protected function getSkinId(): string
    {
        return $this->argument('skinId');
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