<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use GIS\CategoryProduct\Interfaces\ProductInterface;
use GIS\ProductVariation\Traits\InitFirstVariation;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AddVariationToCartWire extends Component
{
    use InitFirstVariation;

    public ProductInterface $product;
    public int $quantity = 1;

    public function mount(): void
    {
        $this->setFirstVariation();
    }

    public function updated($property, $value): void
    {
        if ($property === 'quantity' && $value <= 0) {
            $this->quantity = 1;
        }
    }

    public function render(): View
    {
        return view("vc::livewire.web.catalog.add-variation-to-cart-wire");
    }

    #[On("switch-variation")]
    public function setVariation(int $id): void
    {
        $this->variation = $this->variations->find($id);
        $this->variationId = $id;
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) $this->quantity--;
    }

    public function addToCart(): void
    {
        CartActions::addToCart($this->variation, $this->quantity);
        $this->reset(["quantity"]);
        $this->dispatch("change-cart");
    }
}
