<?php

namespace GIS\VariationCart\Helpers;

use GIS\ProductVariation\Interfaces\ProductVariationInterface;
use GIS\VariationCart\Interfaces\CartInterface;
use GIS\VariationCart\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartActionsManager
{
    public function initCart(): CartInterface
    {
        $cart = $this->getCart();
        if ($cart) return $cart;
        $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
        return $cartModelClass::create([]);
    }

    public function getCart(): ?CartInterface
    {
        $cart = $this->findCartByCookie();
        if ($cart) return $cart;
        return $this->findCartByAuth();
    }

    public function addToCart(ProductVariationInterface $variation, $quantity = 1, CartInterface $customCart = null): CartInterface
    {
        $quantity = intval($quantity);

        $cart = $customCart ?? $this->initCart();
        // Если вариация выключена, вернуть корзину без изменения
        if (! $variation->published_at) {
            session()->flash("addToCart-error", "Товар закончился");
            return $cart;
        }

        $oldQuantity = DB::table("cart_product_variation")
            ->select("quantity")
            ->where("cart_id", $cart->id)
            ->where("product_variation_id", $variation->id)
            ->first();
        if ($oldQuantity) $quantity += $oldQuantity->quantity;

        $cart->variations()->syncWithoutDetaching([
            $variation->id => ["quantity" => $quantity]
        ]);
        $this->recalculateTotal($cart);
        $cart->lastQuantity = $quantity;
//        session()->flash("addToCart-success", "Товар добавлен в корзину");
        return $cart;
    }

    public function changeQuantity(ProductVariationInterface $variation, $quantity = 1, CartInterface $customCart = null): ?CartInterface
    {
        $cart = $customCart ?? $this->initCart();
        if (! $cart) {
            session()->flash("changeQuantity-error", "Корзина не найдена");
            return null;
        }
        if (! $variation->published_at) {
            session()->flash("changeQuantity-error", "Товар закончился");
            $this->deleteItem($variation, $cart);
            return $cart;
        }

        $cart->variations()->syncWithoutDetaching([
            $variation->id => ["quantity" => $quantity]
        ]);
        $this->recalculateTotal($cart);
        $cart->lastQuantity = $quantity;
        return $cart;
    }

    public function deleteItem(ProductVariationInterface $variation, CartInterface $customCart = null): void
    {
        $cart = $customCart ?? $this->getCart();
        if (! $cart) return;
        $cart->variations()->detach($variation);
        $this->recalculateTotal($cart);
        session()->flash("deleteCartItem-success", "Товар удален из корзины");
    }

    public function clearCart(CartInterface $customCart = null): void
    {
        $cart = $customCart ?? $this->getCart();
        if (! $cart) return;
        $cart->variations()->detach();
        $cart->save();
        $this->recalculateTotal($cart);
    }

    public function recalculateTotal(CartInterface $cart): void
    {
        $total = 0;
        $variations = $cart->variations()
            ->select("id", "price", "product_id")
            ->with("product")
            ->get();

        foreach ($variations as $variation) {
            $pivot = $variation->pivot;
            $price = $variation->price;
            $total += $price * $pivot->quantity;
        }
        $cart->total = $total;
        $cart->save();
    }

    public function getCartInfo(CartInterface $cart = null): object
    {
        if (empty($cart)) $cart = $this->getCart();
        if ($cart) {
            $key = "cart-actions-cartInfo:{$cart->id}";
            $data = Cache::rememberForever($key, function () use ($cart) {
                return [
                    "count" => $cart->count,
                    "productHuman" => num2word($cart->count, ["товар", "товара", "товаров"]),
                    "total" => (float) $cart->total, "humanTotal" => $cart->human_total,
                    "saleLess" => (float) $cart->sale_less, "humanSaleLess" => $cart->human_sale_less,
                    "discount" => (float) $cart->discount, "humanDiscount" => $cart->human_discount,
                    "cartUpdated" => $cart->check_updated,
                ];
            });
        } else {
            $data = [
                "count" => 0,
                "productHuman" => num2word(0, ["товар", "товара", "товаров"]),
                "total" => 0, "humanTotal" => "0",
                "saleLess" => 0, "humanSaleLess" => "0",
                "discount" => 0, "humanDiscount" => "0",
                "cartUpdated" => null,
            ];
        }
        return (object) $data;
    }

    public function getCartItems(CartInterface $cart = null): ?array
    {
        if (empty($cart)) $cart = $this->getCart();
        if (! $cart) return null;

        $items = [];
        $collection = $cart->variations()
            ->leftJoin("products", "product_variations.product_id", "=", "products.id")
            ->with("product", "product.cover")
            ->orderBy("price")
            ->orderBy("products.title")
            ->get();

        foreach ($collection as $variation) {
            $product = $variation->product;

            $items[] = (object) [
                "id" => $variation->id . ":" . $variation->product->id . ":" . now()->timestamp,
                "product" => (object) [
                    "model" => $product,
                    "title" => $product->title,
                ],
                "variation" => (object) [
                    "model" => $variation,
                    "title" => $variation->title,
                    "total" => $variation->cart_total,
                    "humanTotal" => $variation->human_cart_total,
                    "oldTotal" => $variation->cart_old_total,
                    "humanOldTotal" => $variation->human_cart_old_total,
                    "sale" => (bool) $variation->sale,
                ],
                "quantity" => $variation->pivot->quantity,
            ];
        }
        return $items;
    }

    public function getCartItemQuantity(int $variationId, CartInterface $cart = null): int
    {
        if (empty($cart)) $cart = $this->getCart();
        if (! $cart) return 0;

        $list = Cache::rememberForever("cart-actions-getCartItemQuantity:{$cart->id}", function () use ($cart) {
            $collection = $cart->variations()->select("id", "quantity")->get();
            $array = [];
            foreach ($collection as $item) {
                $array[$item->id] = $item->quantity;
            }
            return $array;
        });
        if (! empty($list[$variationId])) { return $list[$variationId]; }
        else return 0;
    }

    public function setCookie(CartInterface $cart): void
    {
        if (Auth::check() && $cart->user_id !== Auth::id()) return;
        $cookie = Cookie::make("cartUuid", $cart->id, 60*24*30);
        Cookie::queue($cookie);
    }

    public function forgetCookie(): void
    {
        $cookie = Cookie::forget("cartUuid");
        Cookie::queue($cookie);
    }

    public function clearCartCache(CartInterface $cart): void
    {
        $uuid = $cart->id;
        Cache::forget("cart-actions-cartByUuid:{$uuid}");

        $userId = $cart->user_id;
        if (! empty($userId)) Cache::forget("cart-actions-cartByUserId:{$userId}");

        Cache::forget("cart-actions-cartInfo:{$uuid}");

        Cache::forget("cart-actions-getCartItemQuantity:{$uuid}");
    }

    protected function findCartByAuth(): ?CartInterface
    {
        if (! Auth::check()) return null;
        return $this->findCartByUserId(Auth::id());
    }

    protected function findCartByCookie(): ?CartInterface
    {
        $cookie = Cookie::get("cartUuid", false);
        if (! $cookie) return null;
        $cart = $this->findCartByUuid($cookie);
        if (! $cart) return null;
        // Если в куке была корзина, привязанная к юзверю,
        // а юзверя сейчас нет, то корзина не подходит.
        if (! Auth::check() && ! empty($cart->user_id)) {
            $this->forgetCookie();
            return null;
        }
        $this->checkUserAuthCart($cart);
        return $cart;
    }

    protected function checkUserAuthCart(CartInterface $cart): void
    {
        if (! Auth::check()) return;
        if (! empty($cart->user_id)) return;
        $userCart = $this->findCartByUserId(Auth::id());
        // Если у юзверя была корзина,
        // нужно объединить корзины
        if ($userCart && $userCart->id !== $cart->id) {
            $this->mergeCarts($cart, $userCart);
        }
        $cart->user_id = Auth::id();
        $cart->save();
    }

    protected function mergeCarts(CartInterface $anonymous, CartInterface $userCart): void
    {
        foreach ($userCart->variations as $variation) {
            $pivot = $variation->pivot;
            $quantity = $pivot->quantity;
            $this->addToCart($variation, $quantity, $anonymous);
        }

        try {
            $userCart->delete();
        } catch (\Exception $exception) {
            Log::error("Не удалось удалить корзину {$userCart->id}");
            $userCart->user_id = null;
            $userCart->save();
        }
    }

    protected function findCartByUserId(int $id): ?CartInterface
    {
        $key = "cart-actions-cartByUserId:{$id}";
        return Cache::rememberForever($key, function () use ($id) {
            $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
            try {
                return $cartModelClass::query()
                    ->where("user_id", $id)
                    ->firstOrFail();
            } catch (\Exception $exception) {
                return null;
            }
        });
    }

    protected function findCartByUuid(string $uuid): ?CartInterface
    {
        $key = "cart-actions-cartByUuid:{$uuid}";
        return Cache::rememberForever($key, function () use ($uuid) {
            try {
                $cartModelClass = config("variation-cart.customCartModel") ?? Cart::class;
                return $cartModelClass::query()->findOrFail($uuid);
            } catch (\Exception $exception) {
                return null;
            }
        });
    }
}
