<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use GIS\CategoryProduct\Interfaces\ProductInterface;
use GIS\VariationCart\Traits\ChangeVariationCartQuantityTrait;
use GIS\ProductVariation\Traits\InitFirstVariation;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AddVariationToCartWire extends Component
{
    use InitFirstVariation, ChangeVariationCartQuantityTrait;

    public function mount(): void
    {
        $this->setFirstVariation();
        if ($this->variationId) {
            $this->quantity = CartActions::getCartItemQuantity($this->variationId);
        }
    }

    public function updated($property, $value): void
    {
        if ($property === 'quantity' && $value <= 0) {
            $this->quantity = 0;
        }
    }

    public function render(): View
    {
        return view("vc::livewire.web.catalog.add-variation-to-cart-wire");
    }

    #[On("switch-variation")]
    public function setVariation(int $variationId, int $productId): void
    {
        if ($this->product->id !== $productId) { return; }
        $this->reset("quantity", "variationId");
        $this->variation = $this->variations->find($variationId);
        if (! $this->variation) { return; }
        $this->variationId = $variationId;
        $this->quantity = CartActions::getCartItemQuantity($variationId);
    }
}
