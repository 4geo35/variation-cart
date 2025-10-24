<div class="bg-white rounded-base lg:ml-indent p-indent space-y-indent">
    <x-tt::h3 class="mb-indent">Ваш заказ</x-tt::h3>

    <div class="space-y-indent border-b border-stroke pb-indent mb-indent">
        @foreach($items as $item)
            <x-vc::checkout.item :item="$item" />
        @endforeach
    </div>

    <div class="flex items-end justify-between text-xl mb-indent">
        <span class="text-body/60">Стоимость</span>
        <span class="font-semibold">{{ $info->humanTotal }} руб</span>
    </div>

    <button type="submit" class="btn btn-primary w-full" form="checkoutForm" wire:loading.attr="disabled">
        Оформить заказ
    </button>
</div>
