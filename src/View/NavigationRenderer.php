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
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\Navigation;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class NavigationRenderer
 * @package Dot\Navigation\View
 */
class NavigationRenderer extends AbstractNavigationRenderer
{
    /**
     * @var NavigationOptions
     */
    protected $options;

    /**
     * NavigationMenu constructor.
     * @param Navigation $navigation
     * @param TemplateRendererInterface $template
     * @param NavigationOptions $options
     */
    public function __construct(
        Navigation $navigation,
        TemplateRendererInterface $template,
        NavigationOptions $options
    ) {
        $this->options = $options;
        parent::__construct($navigation, $template);
    }

    /**
     * @param string $partial
     * @param string|NavigationContainer $container
     * @param array $params
     * @return string
     */
    public function renderPartial($container, string $partial, array $params = []): string
    {
        $container = $this->getContainer($container);

        return $this->template->render(
            $partial,
            array_merge(
                ['container' => $container, 'navigation' => $this->navigation],
                $params
            )
        );
    }

    /**
     * @param string|NavigationContainer $container
     * @return string
     */
    public function render($container): string
    {
        // TODO: render a default HTML menu structure
        return '';
    }
}
