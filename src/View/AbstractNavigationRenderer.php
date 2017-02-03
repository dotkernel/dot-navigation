<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\View;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Service\NavigationInterface;
use Zend\Escaper\Escaper;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class AbstractNavigationHelper
 * @package Dot\Navigation\Helper
 */
abstract class AbstractNavigationRenderer implements RendererInterface
{
    /** @var NavigationInterface */
    protected $navigation;

    /** @var  string */
    protected $partial;

    /** @var  NavigationContainer[] */
    protected $containers = [];

    /** @var TemplateRendererInterface */
    protected $template;

    /**
     * AbstractNavigationHelper constructor.
     * @param NavigationInterface $navigation
     * @param TemplateRendererInterface $template
     */
    public function __construct(NavigationInterface $navigation, TemplateRendererInterface $template)
    {
        $this->navigation = $navigation;
        $this->template = $template;
    }

    public function htmlAttributes(array $attributes): string
    {
        $xhtml = '';
        $escaper = new Escaper();

        foreach ($attributes as $key => $val) {
            $key = $escaper->escapeHtml($key);

            if (('on' == substr($key, 0, 2)) || ('constraints' == $key)) {
                // Don't escape event attributes; _do_ substitute double quotes with singles
                if (!is_scalar($val)) {
                    // non-scalar data should be cast to JSON first
                    $val = json_encode($val);
                }
            } else {
                if (is_array($val)) {
                    $val = implode(' ', $val);
                }
            }

            $val = $escaper->escapeHtmlAttr($val);

            if ('id' == $key) {
                $val = $this->normalizeId($val);
            }
            if (strpos($val, '"') !== false) {
                $xhtml .= " $key='$val'";
            } else {
                $xhtml .= " $key=\"$val\"";
            }
        }
        return $xhtml;
    }

    /**
     * Normalize an ID
     *
     * @param  string $value
     * @return string
     */
    protected function normalizeId(string $value): string
    {
        if (strstr($value, '[')) {
            if ('[]' == substr($value, -2)) {
                $value = substr($value, 0, strlen($value) - 2);
            }
            $value = trim($value, ']');
            $value = str_replace('][', '-', $value);
            $value = str_replace('[', '-', $value);
        }
        return $value;
    }

    /**
     * @return string|null
     */
    public function getPartial(): ?string
    {
        return $this->partial;
    }

    /**
     * @param string $partial
     */
    public function setPartial(string $partial)
    {
        $this->partial = $partial;
    }

    /**
     * @param string $container
     * @return NavigationContainer
     */
    protected function getContainer(string $container): NavigationContainer
    {
        if (isset($this->containers[$container])) {
            return $this->containers[$container];
        }

        if (is_string($container)) {
            $this->containers[$container] = $this->navigation->getContainer($container);
        }

        return $this->containers[$container];
    }

    /**
     * Cleans array of attributes based on valid input.
     *
     * @param array $input
     * @param array $valid
     * @return array
     */
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
