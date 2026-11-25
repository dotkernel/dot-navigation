<?php

declare(strict_types=1);

namespace Dot\Navigation;

use function count;
use function is_scalar;
use function is_string;

class Page extends NavigationContainer
{
    protected ?Page $parent = null;
    /** @var array<string, mixed> */
    protected array $attributes = [];
    /** @var array<string, mixed> */
    protected array $options = [];

    public function hasParent(): bool
    {
        return $this->parent instanceof Page;
    }

    public function getParent(): ?Page
    {
        return $this->parent;
    }

    public function setParent(Page $parent): void
    {
        $this->parent = $parent;
    }

    public function addPage(Page $page): void
    {
        $page->setParent($this);
        parent::addPage($page);
    }

    public function setOption(string $option, mixed $value): void
    {
        $this->options[$option] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOptions(): bool
    {
        return count($this->options) > 0;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function setAttribute(string $attribute, mixed $value): void
    {
        $this->attributes[$attribute] = $value;
    }

    public function getAttribute(string $attribute): mixed
    {
        return $this->attributes[$attribute] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function hasAttributes(): bool
    {
        return count($this->attributes) > 0;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getName(): ?string
    {
        $name = $this->getOption('name');

        return is_scalar($name) ? (string) $name : null;
    }

    public function getOption(string $option): mixed
    {
        return $this->options[$option] ?? null;
    }

    public function getLabel(): string
    {
        $label = $this->getOption('label');

        return ! is_string($label) || empty($label) ? 'Not defined' : $label;
    }
}
