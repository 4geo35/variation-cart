<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use GIS\ProductVariation\Interfaces\ProductVariationInterface;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartListWire extends Component
{
    public array|null $items = null;

    public function mount(): void
    {
        $this->setItems();
    }

    public function render(): View
    {
        return view('vc::livewire.web.catalog.cart-list-wire');
    }

    #[On("change-quantity")]
    public function changeQuantity(string $variationId, int $quantity): void
    {
        $result = $this->findById($variationId);
        if ($result) {
            $variation = $result->variation->model;
            /**
             * @var ProductVariationInterface $variation
             */
            $variation->fresh();
            CartActions::changeQuantity($variation, $quantity);
            $this->fireChangeCart();
        }
        $this->setItems();
    }

    #[On("delete-item")]
    public function deleteItem(string $variationId): void
    {
        $result = $this->findById($variationId);
        if ($result) {
            $variation = $result->variation->model;
            /**
             * @var ProductVariationInterface $variation
             */
            CartActions::deleteItem($variation);
            $this->fireChangeCart();
        }
        $this->setItems();
    }

    protected function findById(string $id): ?object
    {
        $collection = collect($this->items);
        return $collection->filter(function (object $item, int $key) use ($id) {
            return $item->id === $id;
        })->first();
    }

    protected function fireChangeCart(): void
    {
        $this->dispatch("change-cart");
    }

    protected function setItems(): void
    {
        $this->items = CartActions::getCartItems();
    }
}
