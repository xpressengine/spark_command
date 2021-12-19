<?php

namespace XeHub\XePlugin\XeCli;

/**
 * Class VariableDeclaration
 *
 * 변수 선언
 *
 * @TODO https://github.com/nette/php-generator.git
 * @package XeHub\XePlugin\XeCli
 */
class DeclarationVariable
{
    /**
     * Declared Variable's Access Modifier
     * 접근제어자 (public, private, protected)
     *
     * @var string
     */
    protected $accessModifier = 'protected';

    /**
     * Declared Variable's Value
     *
     * @var string|null
     */
    protected $value;

    /**
     * Declared Variable's Name
     *
     * @var string
     */
    protected $name;

    /**
     * Declared Variable's Type
     *
     * @var string
     */
    protected $type = 'mixed';

    /**
     * Declared Variable's Description
     *
     * @var string|null
     */
    protected $description;

    /**
     * DeclarationVariable __construct
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Set AccessModifier
     *
     * @param string $accessModifier
     * @return DeclarationVariable
     */
    public function setAccessModifier(string $accessModifier): DeclarationVariable
    {
        $accessModifiers = [
            'public',
            'private',
            'protected'
        ];

        if (in_array($accessModifier, $accessModifiers) === false) {
            throw new \InvalidArgumentException('Please enter one of the three public, private, and protected.');
        }

        $this->accessModifier = $accessModifier;
        return $this;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return DeclarationVariable
     */
    public function setName(string $name): DeclarationVariable
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set Type
     *
     * @param string $type
     * @return DeclarationVariable
     */
    public function setType(string $type): DeclarationVariable
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set Description
     *
     * @param $description
     * @return DeclarationVariable
     */
    public function setDescription($description): DeclarationVariable
    {
        if (is_string($description) === false && is_null($description) === false) {
            throw new \InvalidArgumentException('Please enter only string and null type.');
        }

        $this->description = $description;
        return $this;
    }

    /**
     * Set Value
     *
     * @param string|null $value
     * @return DeclarationVariable
     */
    public function setValue($value): DeclarationVariable
    {
        if (is_string($value) === false && is_null($value) === false) {
            throw new \InvalidArgumentException('Please enter only string and null type.');
        }

        $this->value = $value;
        return $this;
    }

    /**
     * Get Full Declaration
     *
     * @return string
     */
    public function fullDeclaration(): string
    {
        return implode("\n", [
            $this->commentDeclaration(),
            $this->variableDeclaration()
        ]);
    }

    /**
     * Get Comment Declaration
     *
     * @return string
     */
    protected function commentDeclaration(): string
    {
        $comments = [];
        $comments[] = '/**';

        if ($this->description !== null) {
            $comments[] = "\t * {$this->description}";
            $comments[] = "\t *";
        }

        $comments[] = "\t * @var {$this->type}";
        $comments[] = "\t */";

        return implode("\n", $comments);
    }

    /**
     * Get Variable Declaration
     *
     * @return string
     */
    protected function variableDeclaration(): string
    {
        $variableDeclaration = "\t{$this->accessModifier} \${$this->name}";

        if ($this->value !== null) {
            $variableDeclaration .= " = {$this->value}";
        }

        return $variableDeclaration . ';';
    }
}