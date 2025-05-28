<div class="row">
    <div class="col w-5/12">
        <div class="flex items-center justify-start">
            <a href="{{ route('web.product', ["product" => $item->product->model]) }}" class="mr-indent">
                @if ($item->product->imgUrl)
                    <img src="{{ $item->product->imgUrl }}" alt="{{ $item->product->title }}" class="rounded">
                @else
                    <span class="inline-flex items-center justify-center w-[120px] h-[90px]">
                        <x-tt::ico.image width="72px" height="72px" />
                    </span>
                @endif
            </a>
            <div class="space-y-1">
                <div>
                    <a href="{{ route('web.product', ["product" => $item->product->model]) }}" class="hover:text-primary-hover">
                        {{ $item->product->title }}
                    </a>
                </div>
                <div class="text-secondary">{{ $item->variation->title }}</div>
                <button type="button" class="text-danger hover:text-danger-hover inline-flex" wire:click="removeItem">
                    <x-tt::ico.trash /> <span class="pl-1">Удалить</span>
                </button>
            </div>
        </div>
    </div>

    <div class="col w-3/12 flex justify-center">
        <div>
            <div class="font-semibold">
                {{ $item->variation->humanTotal }} руб.
            </div>
            @if ($item->variation->sale)
                <div class="font-semibold text-sm line-through text-secondary">
                    {{ $item->variation->humanOldTotal }} руб.
                </div>
            @endif
        </div>
    </div>

    <div class="col w-4/12">
        <div class="flex items-center justify-start mr-indent-half mb-indent-half">
            <button type="button" class="btn btn-outline-secondary rounded-e-none border-e-0"
                    wire:click="decreaseQuantity"
                    @if ($quantity <= 1) disabled @endif>
                <x-vc::ico.minus />
            </button>
            <input type="number" aria-label="Количество"
                   class="form-control border-secondary text-center rounded-none max-w-24 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                   wire:model.blur="quantity" min="1">
            <button type="button" class="btn btn-outline-secondary rounded-s-none border-s-0"
                    wire:click="increaseQuantity">
                <x-vc::ico.plus />
            </button>
        </div>
    </div>
</div>
