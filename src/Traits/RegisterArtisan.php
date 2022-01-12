<?php

namespace XeHub\XePlugin\XeCli\Traits;

use Illuminate\Console\Application as Artisan;

/**
 * Trait RegisterArtisan
 *
 * @package XeHub\XePlugin\XeCli\Traits
 */
trait RegisterArtisan
{
    /**
     * 위젯 생성 코멘드를 `Artisan`에 등록합니다.
     *
     * @return void
     */
    public static function register()
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands(static::class);
        });
    }
}
