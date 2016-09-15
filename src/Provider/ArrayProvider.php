<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/3/2016
 * Time: 8:09 PM
 */

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

class ArrayProvider implements ProviderInterface
{
    /**
     * @var NavigationContainer
     */
    protected $navContainer;

    /**
     * @var array
     */
    protected $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return NavigationContainer
     */
    public function getContainer()
    {
        if($this->navContainer instanceof NavigationContainer) {
            return $this->navContainer;
        }

        $this->navContainer = new NavigationContainer();
        foreach ($this->config as $page)
        {
            $page = $this->getPage($page);
            $this->navContainer->addPage($page);
        }

        return $this->navContainer;
    }

    /**
     * @param array $spec
     * @return Page
     */
    protected function getPage(array $spec)
    {
        $page = new Page();

        if(isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if(isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        if(isset($spec['pages'])) {
            foreach ($spec['pages'] as $childSpec)
            {
                $page->addPage($this->getPage($childSpec));
            }
        }

        return $page;
    }
}