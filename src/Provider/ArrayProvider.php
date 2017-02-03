<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

/**
 * Class ArrayProvider
 * @package Dot\Navigation\Provider
 */
class ArrayProvider implements ProviderInterface
{
    /**
     * @var NavigationContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * ArrayProvider constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return NavigationContainer
     */
    public function getContainer()
    {
        if ($this->container instanceof NavigationContainer) {
            return $this->container;
        }

        $this->container = new NavigationContainer();
        foreach ($this->config as $page) {
            $page = $this->getPage($page);
            $this->container->addPage($page);
        }

        return $this->container;
    }

    /**
     * @param array $spec
     * @return Page
     */
    protected function getPage(array $spec)
    {
        $page = new Page();

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        if (isset($spec['pages'])) {
            foreach ($spec['pages'] as $childSpec) {
                $page->addPage($this->getPage($childSpec));
            }
        }

        return $page;
    }
}
