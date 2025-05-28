<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use GIS\VariationCart\Facades\CartActions;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartInfoWire extends Component
{
    public object $info;

    public function mount(): void
    {
        $this->setCartInfo();
    }

    public function render(): View
    {
        return view('vc::livewire.web.catalog.cart-info-wire');
    }

    #[On("change-cart")]
    public function setCartInfo(): void
    {
        $this->info = CartActions::getCartInfo();
    }
}
