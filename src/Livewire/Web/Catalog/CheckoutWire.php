<?php

namespace GIS\VariationCart\Livewire\Web\Catalog;

use GIS\ProductVariation\Facades\OrderActions;
use GIS\ProductVariation\Interfaces\OrderInterface;
use GIS\ProductVariation\Interfaces\ProductVariationInterface;
use GIS\ProductVariation\Models\Order;
use GIS\VariationCart\Facades\CartActions;
use Illuminate\View\View;
use Livewire\Component;

class CheckoutWire extends Component
{
    public object $info;
    public array|null $items = null;
    public string $startCheckout;

    public string $name = "";
    public string $email = "";
    public string $phone = "";
    public string $comment = "";
    public bool $policy = true;

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:150"],
            "email" => ["required_without:phone", "string", "email", "max:150"],
            "phone" => ["required_without:email", "string", "max:150"],
            "comment" => ["nullable", "string"],
            "policy" => ["required", "accepted"],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            "name" => "Имя",
            "email" => "E-mail",
            "phone" => "Телефон",
            "comment" => "Комментарий",
            "policy" => "Согласие с политикой конфиденциальности"
        ];
    }

    public function mount(): void
    {
        $this->setStartTime();
        $this->setInfo();
        $this->setItems();
    }

    public function render(): View
    {
        return view('vc::livewire.web.catalog.checkout-wire');
    }

    public function store(): void
    {
        if (! $this->checkCartDate()) return;
        $this->validate();

        $orderModelClass = config("product-variation.customOrderModel") ?? Order::class;
        $order = $orderModelClass::create([]);
        /**
         * @var OrderInterface $order
         */
        $this->addCustomerToOrder($order);
        foreach ($this->items as $item) {
            $variation = $item->variation->model;
            /**
             * @var ProductVariationInterface $variation
             */
            OrderActions::addVariationsToOrder($order, [
                $variation->id => (object)[
                    "quantity" => $item->quantity,
                ]
            ]);
        }
        $this->resetFields();
        CartActions::clearCart();
        session()->flash("checkoutCart-success", "Ваш заказ № {$order->number} оформлен");
        $this->redirectRoute("web.cart");
    }

    protected function resetFields(): void
    {
        $this->reset(["name", "email", "phone", "comment"]);
    }

    protected function checkCartDate(): bool
    {
        $this->setInfo();
        if ($this->info->cartUpdated > $this->startCheckout) {
            session()->flash("error", "Корзина была изменена");
            $this->setItems();
            $this->setStartTime();
            return false;
        }
        return true;
    }

    protected function addCustomerToOrder(OrderInterface $order): void
    {
        $order->customer()->create([
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "comment" => $this->comment,
        ]);
    }

    protected function setInfo(): void
    {
        $this->info = CartActions::getCartInfo();
    }

    protected function setItems(): void
    {
        $this->items = CartActions::getCartItems();
    }

    protected function setStartTime(): void
    {
        $this->startCheckout = date_helper()->format(date_helper()->changeTz(now()->toString()), "Y-m-d H:i:s");
    }
}
