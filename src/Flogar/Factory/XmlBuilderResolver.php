<?php

declare(strict_types=1);

namespace Flogar\Factory;

use Flogar\Builder\BuilderInterface;
use Flogar\Model\Voided\Reversion;
use Flogar\Xml\Builder\VoidedBuilder;

class XmlBuilderResolver
{
    /**
     * @var array
     */
    private $options;

    /**
     * XmlBuilderResolver constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function find(string $docClass): BuilderInterface
    {
        $builder = $this->findBuilderType($docClass);

        return new $builder($this->options);
    }

    private function findBuilderType(string $docClass): string
    {
        if ($docClass === Reversion::class) {
            return VoidedBuilder::class;
        }

        $className = substr(strrchr($docClass, '\\'), 1);

        return "Flogar\\Xml\\Builder\\${className}Builder";
    }
}
