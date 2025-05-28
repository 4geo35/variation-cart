<div class="card">
    <div class="card-body">
        <x-tt::h3 class="mb-indent">Ваш заказ</x-tt::h3>

        <div class="space-y-indent-half border-b border-secondary pb-indent-half mb-indent-half">
            @foreach($items as $item)
                <x-vc::checkout.item :item="$item" />
            @endforeach
        </div>

        <div class="flex items-end justify-between text-xl mb-indent">
            <span class="font-semibold">Стоимость</span>
            <span class="font-semibold">{{ $info->humanTotal }} руб</span>
        </div>

        <button type="submit" class="btn btn-primary w-full" form="checkoutForm" wire:loading.attr="disabled">
            Оформить заказ
        </button>
    </div>
</div>
