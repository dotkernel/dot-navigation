<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\View;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Filter\IsAllowedFilter;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\MenuOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Service\Navigation;
use Zend\Expressive\Template\TemplateRendererInterface;

class NavigationRenderer extends AbstractNavigationRenderer
{
    /**
     * @var MenuOptions
     */
    protected $options;

    /**
     * NavigationMenu constructor.
     * @param Navigation $navigation
     * @param TemplateRendererInterface $template
     * @param MenuOptions $options
     */
    public function __construct(
        Navigation $navigation,
        TemplateRendererInterface $template,
        MenuOptions $options
    ) {
        $this->options = $options;
        parent::__construct($navigation, $template);
    }

    /**
     * @param string|NavigationContainer|null $container
     * @return string
     */
    public function renderMenu($container = null)
    {
        $html = '';
        $filter = new IsAllowedFilter($this->getContainer($container), $this->navigation);
        $iterator = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth($this->options->getMaxDepth());

        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();

            if ($depth == $this->options->getMinDepth()) {
                $prevDepth = $depth;
                continue;
            }

            if ($depth > $prevDepth) {
                $html .= sprintf('<ul%s>',
                    $prevDepth == $this->options->getMinDepth()
                        ? ' class="' . $this->options->getUlClass() . '"'
                        : '');
            } elseif ($prevDepth > $depth) {
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $html .= '</li>';
                    $html .= '</ul>';
                }
                $html .= '</li>';
            } else {
                $html .= '</li>';
            }

            $liClass = $this->navigation->isActive($page) ? ' class="' . $this->options->getActiveClass() . '"' : '';
            $html .= sprintf('<li%s>%s', $liClass, $this->htmlify($page));

            $prevDepth = $depth;
        }

        if ($html) {
            for ($i = $prevDepth + 1; $i > 0; $i--) {
                $html .= '</li></ul>';
            }
        }

        return $html;
    }

    /**
     * @param null|string|NavigationContainer $container
     * @param null|string $partial
     * @param array $extra
     * @return string
     */
    public function renderPartial($container = null, $partial = null, array $extra = [])
    {
        $container = $this->getContainer($container);

        if (null === $partial) {
            $partial = $this->getPartial();
        }

        if (empty($partial)) {
            throw new RuntimeException('Unable to render menu: no partial template provided');
        }

        return $this->template->render($partial,
            array_merge(['container' => $container, 'navigation' => $this->navigation], $extra));
    }

    /**
     * Default render
     * @param null|string|NavigationContainer $container
     * @return string
     */
    public function render($container = null)
    {
        $container = $this->getContainer($container);
        if ($this->getPartial()) {
            return $this->renderPartial($container);
        }

        return $this->renderMenu($container);
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function htmlify(Page $page)
    {
        if ($page->getOption('label')) {
            $label = $page->getOption('label');
        } else {
            $label = $page->getName();
        }

        $href = null;
        try {
            $href = $this->navigation->getHref($page);
        } catch (RuntimeException $e) {
            ; //intentionally left blank
        }

        $attributes = $page->getAttributes();
        if ($href) {
            $element = 'a';
            $attributes['href'] = $href;
        } else {
            $element = 'span';
        }

        return sprintf('<%s%s>%s</%s>', $element, $this->htmlAttributes($attributes), $label, $element);
    }
}