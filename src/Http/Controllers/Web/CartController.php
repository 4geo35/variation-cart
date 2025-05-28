<?php

namespace GIS\VariationCart\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use GIS\Metable\Facades\MetaActions;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function page(): View
    {
        $cartInfo = CartActions::getCartInfo();
        $metas = MetaActions::renderByPage("cart");
        return view("vc::web.cart.page", compact("cartInfo", "metas"));
    }

    public function checkout(): View|RedirectResponse
    {
        $cartInfo = CartActions::getCartInfo();
        if ($cartInfo->count <= 0) {
            return redirect()->route("web.cart");
        }
        return view("vc::web.cart.checkout");
    }
}
