<div class="space-y-indent-half">
    <x-tt::notifications.success prefix="deleteCartItem-" />
    <x-tt::notifications.error prefix="deleteCartItem-" />

    <x-tt::notifications.success prefix="changeQuantity-" />
    <x-tt::notifications.error prefix="changeQuantity-" />

    <x-tt::notifications.success prefix="checkoutCart-" />
    <x-tt::notifications.error prefix="checkoutCart-" />

    @if (empty($items))
        <div>Корзина пуста <a href="{{ route('web.catalog') }}" class="text-primary hover:text-primary-hover">за покупками</a></div>
    @else
        <div class="flex flex-col gap-y-indent">
            @foreach($items as $item)
                <livewire:vc-cart-list-item :key="$item->id" :item="$item" />
            @endforeach
        </div>
    @endif
</div>
