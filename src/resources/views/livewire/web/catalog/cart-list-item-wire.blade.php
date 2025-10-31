<div class="pb-indent border-b border-stroke">
    <div class="row">
        <div class="col w-full sm:w-2/3">
            <div class="flex items-start justify-start">
                <a href="{{ route('web.product', ["product" => $product]) }}" class="block w-[122px] h-[122px] mr-indent shrink-0">
                    @if ($product->cover)
                        <img src="{{ route("thumb-img", ["template" => "cart-teaser", "filename" => $product->cover->file_name]) }}" alt="{{ $item->product->title }}" class="rounded h-full object-cover">
                    @else
                        <span class="inline-flex items-center justify-center w-full h-full">
                        <x-tt::ico.image width="72px" height="72px" />
                    </span>
                    @endif
                </a>
                <div class="">
                    <a href="{{ route('web.product', ["product" => $product]) }}" class="inline-block text-h5-mobile sm:text-h5 hover:text-primary-hover">
                        {{ $item->product->title }}
                    </a>

                    <div class="text-body/60 mt-indent-half">{{ $item->variation->title }}</div>

                    <div class="flex flex-wrap items-center justify-start space-x-indent">
                        @includeIf("pf::web.favorite.text-switcher")
                        <button type="button" class="inline-flex items-center cursor-pointer text-lg hover:text-danger-hover space-x-2 mt-indent-half"
                                wire:click="removeItem">
                            <span class="text-danger"><x-tt::ico.trash /></span> <span>Удалить</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col w-full sm:w-1/3">
            <div class="flex sm:flex-col justify-between items-center sm:items-end h-full space-y-indent-half my-indent-half sm:my-0">
                <div>
                    <div class="text-h3-mobile xl:text-h3 font-semibold">
                        {{ $item->variation->humanTotal }} р. <span class="text-body/60 text-base">/ {{ $item->variation->unit }}</span>
                    </div>
                    @if ($item->variation->sale)
                        <div class="mt-2 text-h4-mobile xl:text-h4 font-semibold line-through text-body/60 sm:text-right">
                            {{ $item->variation->humanOldTotal }} р.
                        </div>
                    @endif
                </div>

                <div class="btn bg-white px-btn-x-ico cursor-default text-base border-secondary overflow-hidden" wire:loading.class="opacity-25">
                    <button type="button" class="cursor-pointer text-base hover:text-primary-hover disabled:text-body/60 disabled:cursor-default"
                            wire:click="decreaseQuantity" wire:loading.class="cursor-default"
                            @if ($quantity <= 1) disabled @else wire:loading.attr="disabled" @endif>
                        <x-vc::ico.minus />
                    </button>
                    <input type="number" aria-label="Количество"
                           class="form-control border-0 rounded-none text-center max-w-20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                           wire:model.blur="quantity" min="1">
                    <button type="button" class="cursor-pointer text-base hover:text-primary-hover"
                            wire:click="increaseQuantity" wire:loading.class="cursor-default" wire:loading.attr="disabled">
                        <x-vc::ico.plus />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
