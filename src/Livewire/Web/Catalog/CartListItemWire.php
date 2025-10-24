<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use Illuminate\View\View;
use Livewire\Component;

class CartListItemWire extends Component
{
    public object $item;
    public int $quantity = 1;

    public function mount(): void
    {
        $this->setRealQuantity();
    }

    public function updated($property, $value): void
    {
        if ($property === "quantity") {
            if ($value <= 0) $this->quantity = 1;
            $this->updateQuantity();
        }
    }

    public function render(): View
    {
        $product = $this->item->product->model;
        return view('vc::livewire.web.catalog.cart-list-item-wire', compact('product'));
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
        $this->updateQuantity();
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->updateQuantity();
        }
    }

    public function removeItem(): void
    {
        $this->dispatch("delete-item", variationId: $this->item->id);
    }

    public function setRealQuantity(): void
    {
        $this->quantity = $this->item->quantity;
    }

    protected function updateQuantity(): void
    {
        $this->dispatch("change-quantity", variationId: $this->item->id, quantity: $this->quantity);
    }
}
