<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
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
