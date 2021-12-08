<?php

namespace XeHub\XePlugin\XeCli\Traits;

/**
 * Trait DeclarationTrait
 *
 * @package XeHub\XePlugin\XeCli\Traits
 */
trait DeclarationTrait
{
    /**
     * Get Property Declaration
     *
     * @param string $accessModifier
     * @param string $varName
     * @param string $type
     * @param string $value
     * @return string
     */
    protected function getPropertyDeclaration(
        string $accessModifier,
        string $varName,
        string $type,
        string $value
    ): string
    {
        if ($type === 'string') {
            $value = "'" . $value . "'";
        }

        return implode("\n", [
            '/**',
            "\t * @var {$type}",
            "\t */",
            "\t{$accessModifier} \${$varName} = {$value};"
        ]);
    }
}
