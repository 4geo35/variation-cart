<div class="card">
    <div class="card-body">
        <div class="flex justify-between items-end mb-indent">
            <x-tt::h3>Ваша корзина</x-tt::h3>
            <div>{{ $info->count }} {{ $info->productHuman }}</div>
        </div>

        <div class="flex justify-between items-end">
            <div>Итого</div>
            <div>{{ $info->humanTotal }} руб.</div>
        </div>

        @if (config("variation-cart.showDiscount") && $info->discount > 0)
            <div class="flex justify-between items-end">
                <div>Скидка</div>
                <div class="text-danger">{{ $info->humanDiscount }} руб.</div>
            </div>
        @endif

        @if ($info->count)
            <a href="{{ route('web.checkout') }}" class="btn btn-primary mt-indent w-full">
                Перейти к оформлению
            </a>
        @endif
    </div>
</div>
