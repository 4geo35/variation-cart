<div class="bg-white rounded-base lg:ml-indent p-indent space-y-indent">
    <div class="flex justify-between items-end">
        <x-tt::h3>Ваша корзина</x-tt::h3>
        <div class="text-body/60 text-nowrap">{{ $info->count }} {{ $info->productHuman }}</div>
    </div>

    <div class="space-y-indent-half">
        <div class="flex justify-between items-end">
            <div class="text-body/60">Итого</div>
            <div class="text-xl font-semibold">{{ $info->humanTotal }} р.</div>
        </div>

        @if (config("variation-cart.showDiscount") && $info->discount > 0)
            <div class="flex justify-between items-end">
                <div class="text-body/60">Скидка</div>
                <div class="text-xl font-semibold text-body/60">{{ $info->humanDiscount }} р.</div>
            </div>
        @endif
    </div>

    @if ($info->count)
        <a href="{{ route('web.checkout') }}" class="btn btn-primary mt-indent w-full">
            Перейти к оформлению
        </a>
    @endif
</div>
