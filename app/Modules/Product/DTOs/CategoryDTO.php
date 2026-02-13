<?php

declare(strict_types=1);

namespace App\Modules\Product\DTOs;

use App\Modules\Product\Models\Category;
use Illuminate\Support\Collection;

readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description = null,
        public ?int $parentId = null,
        public ?string $parentName = null,
        public bool $isActive = true,
        public int $sortOrder = 0,
        public int $productsCount = 0,
        public ?Collection $children = null,
    ) {}

    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            description: $category->description,
            parentId: $category->parent_id,
            parentName: $category->parent?->name,
            isActive: $category->is_active,
            sortOrder: $category->sort_order,
            productsCount: $category->products()->count(),
            children: $category->children?->map(fn ($child) => self::fromModel($child)),
        );
    }

    /**
     * Convert DTO to array for responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parentId,
            'parent_name' => $this->parentName,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
            'products_count' => $this->productsCount,
            'children' => $this->children?->map(fn ($child) => $child->toArray())->toArray(),
        ];
    }
}
