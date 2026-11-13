<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Navigation;

use Illuminate\Support\Str;

final class NavigationRegistry
{
    /**
     * @var array<string, NavigationItem>
     */
    private array $items = [];

    /**
     * @var array<string, NavigationGroup>
     */
    private array $groups = [];

    public function add(NavigationItem $item): self
    {
        $this->items[$item->id] = $item;

        return $this;
    }

    public function remove(string $id): self
    {
        unset($this->items[$id]);

        return $this;
    }

    public function group(NavigationGroup $group): self
    {
        $this->groups[$group->id] = $group;

        return $this;
    }

    public function has(string $id): bool
    {
        return isset($this->items[$id]);
    }

    public function get(string $id): ?NavigationItem
    {
        return $this->items[$id] ?? null;
    }

    /**
     * @return array<string, NavigationItem>
     */
    public function all(): array
    {
        return $this->sortItems($this->items);
    }

    /**
     * @return list<array{group: NavigationGroup, items: list<NavigationItem>}>
     */
    public function grouped(): array
    {
        $items = $this->all();
        $groups = $this->groups;

        $groups['main'] ??= NavigationGroup::make('main', 'Main', 0);

        foreach ($items as $item) {
            $groups[$item->group] ??= NavigationGroup::make($item->group, Str::headline($item->group));
        }

        uasort($groups, static function (NavigationGroup $left, NavigationGroup $right): int {
            return [$left->order, $left->id] <=> [$right->order, $right->id];
        });

        $grouped = [];

        foreach ($groups as $group) {
            $groupItems = array_values(array_filter(
                $items,
                static fn (NavigationItem $item): bool => $item->group === $group->id,
            ));

            if ($groupItems === []) {
                continue;
            }

            $grouped[] = [
                'group' => $group,
                'items' => $groupItems,
            ];
        }

        return $grouped;
    }

    /**
     * @param  array<string, NavigationItem>  $items
     * @return array<string, NavigationItem>
     */
    private function sortItems(array $items): array
    {
        uasort($items, static function (NavigationItem $left, NavigationItem $right): int {
            return [$left->order, $left->id] <=> [$right->order, $right->id];
        });

        return $items;
    }
}
