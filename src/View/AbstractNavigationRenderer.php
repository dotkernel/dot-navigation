<?php

declare(strict_types=1);

namespace Dot\Navigation\View;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Service\NavigationInterface;
use Laminas\Escaper\Escaper;
use Mezzio\Template\TemplateRendererInterface;

use function implode;
use function in_array;
use function is_array;
use function is_scalar;
use function is_string;
use function json_encode;
use function preg_match;
use function str_contains;
use function str_ends_with;
use function str_replace;
use function str_starts_with;
use function strlen;
use function substr;
use function trim;

abstract class AbstractNavigationRenderer implements RendererInterface
{
    protected NavigationInterface $navigation;
    protected TemplateRendererInterface $template;
    protected string $partial;

    public function __construct(NavigationInterface $navigation, TemplateRendererInterface $template)
    {
        $this->navigation = $navigation;
        $this->template   = $template;
    }

    public function htmlAttributes(array $attributes): string
    {
        $xhtml   = '';
        $escaper = new Escaper();

        foreach ($attributes as $key => $val) {
            $key = $escaper->escapeHtml($key);

            if ((str_starts_with($key, 'on')) || ('constraints' === $key)) {
                // Don't escape event attributes; _do_ substitute double quotes with singles
                if (! is_scalar($val)) {
                    // non-scalar data should be cast to JSON first
                    $val = json_encode($val);
                }
            } else {
                if (is_array($val)) {
                    $val = implode(' ', $val);
                }
            }

            $val = $escaper->escapeHtmlAttr($val);

            if ('id' === $key) {
                $val = $this->normalizeId($val);
            }
            if (str_contains($val, '"')) {
                $xhtml .= " $key='$val'";
            } else {
                $xhtml .= " $key=\"$val\"";
            }
        }
        return $xhtml;
    }

    protected function normalizeId(string $value): string
    {
        if (str_contains($value, '[')) {
            if (str_ends_with($value, '[]')) {
                $value = substr($value, 0, strlen($value) - 2);
            }
            $value = trim($value, ']');
            $value = str_replace('][', '-', $value);
            $value = str_replace('[', '-', $value);
        }
        return $value;
    }

    public function getPartial(): ?string
    {
        return $this->partial;
    }

    public function setPartial(string $partial): void
    {
        $this->partial = $partial;
    }

    protected function getContainer(string|NavigationContainer $container): NavigationContainer
    {
        if (is_string($container)) {
            return $this->navigation->getContainer($container);
        } elseif (! $container instanceof NavigationContainer) {
            throw new RuntimeException('Container must be a string or an instance of ' . NavigationContainer::class);
        }

        return $container;
    }

    protected function cleanAttributes(array $input, array $valid): array
    {
        foreach ($input as $key => $value) {
            if (preg_match('/^data-(.+)/', $key) || in_array($key, $valid)) {
                continue;
            }
            unset($input[$key]);
        }
        return $input;
    }
}
