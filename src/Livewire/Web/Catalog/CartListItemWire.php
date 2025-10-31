<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use Illuminate\View\View;
use Livewire\Component;

class CartListItemWire extends Component
{
    public object $item;
    public int $quantity = 1;
    public int $minimal;

    public function mount(): void
    {
        $this->minimal = $this->item->variation->minimalOrder ?? 1;
        $this->setRealQuantity();
    }

    public function updated($property, $value): void
    {
        if ($property === "quantity") {
            if ($value < $this->minimal) $this->quantity = $this->minimal;
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
        if ($this->quantity > $this->minimal) {
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
