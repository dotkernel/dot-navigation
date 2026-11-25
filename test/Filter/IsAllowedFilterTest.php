<?php

declare(strict_types=1);

namespace DotTest\Navigation\Filter;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Filter\IsAllowedFilter;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Service\Navigation;
use PHPUnit\Framework\TestCase;
use RecursiveIteratorIterator;

use function array_map;
use function iterator_to_array;

class IsAllowedFilterTest extends TestCase
{
    public function testWillAcceptAllPagesWithoutAuthorization(): void
    {
        $filter = new IsAllowedFilter($this->getContainer(), $this->getNavigation(null));

        $this->assertSame(['public', 'private', 'private child', 'other'], $this->getLabels($filter));
    }

    public function testWillSkipPageThatIsNotAllowedAndItsSubPages(): void
    {
        $authorization = $this->createStub(AuthorizationInterface::class);
        $authorization->method('isGranted')->willReturnCallback(
            static fn (string $permission): bool => $permission !== 'private'
        );

        $filter = new IsAllowedFilter($this->getContainer(), $this->getNavigation($authorization));

        $this->assertSame(['public', 'other'], $this->getLabels($filter));
    }

    public function testGetChildrenFiltersTheSubPagesOfTheCurrentPage(): void
    {
        $authorization = $this->createStub(AuthorizationInterface::class);
        $authorization->method('isGranted')->willReturnCallback(
            static fn (string $permission): bool => $permission !== 'hidden'
        );

        $visible = new Page();
        $visible->setOptions(['label' => 'visible', 'permission' => 'public']);
        $hidden = new Page();
        $hidden->setOptions(['label' => 'hidden', 'permission' => 'hidden']);
        $parent = new Page();
        $parent->addPages([$visible, $hidden]);

        $filter = new IsAllowedFilter(new NavigationContainer([$parent]), $this->getNavigation($authorization));
        $filter->rewind();

        $this->assertSame(['visible'], $this->getLabels($filter->getChildren()));
    }

    private function getNavigation(?AuthorizationInterface $authorization): Navigation
    {
        return new Navigation(
            $this->createStub(FactoryInterface::class),
            $this->createStub(RouteHelper::class),
            new NavigationOptions(),
            $authorization
        );
    }

    private function getContainer(): NavigationContainer
    {
        $public = new Page();
        $public->setOptions(['label' => 'public', 'permission' => 'public']);

        $child = new Page();
        $child->setOptions(['label' => 'private child', 'permission' => 'public']);

        $private = new Page();
        $private->setOptions(['label' => 'private', 'permission' => 'private']);
        $private->addPage($child);

        $other = new Page();
        $other->setOptions(['label' => 'other']);

        return new NavigationContainer([$public, $private, $other]);
    }

    /**
     * @return list<string>
     */
    private function getLabels(IsAllowedFilter $filter): array
    {
        $iterator = new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::SELF_FIRST);

        return array_map(
            static fn (Page $page): string => $page->getLabel(),
            iterator_to_array($iterator, false)
        );
    }
}
