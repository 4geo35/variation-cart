<div class="space-y-indent-half {{ !$variationId ? 'hidden' : '' }}">
    @if ($variationId)
        <div class="flex flex-wrap items-center justify-start">
            @if (!$quantity)
                <div class="flex flex-col lg:flex-row items-start lg:items-center">
                    <button type="button" class="btn btn-primary mt-indent-half lg:mr-indent-half shrink-0"
                            wire:click="addToCart" wire:loading.attr="disabled">
                        В корзину
                    </button>
                    @if ($variation && $variation->minimal_order)
                        <div class="mt-indent-half text-body/60 text-sm">
                            Минимум <span class="text-nowrap font-semibold">{{ $variation->minimal_order }} {{ $variation->unit_text }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="btn px-btn-x-ico cursor-default text-base border-primary mt-indent-half" wire:loading.class="opacity-25">
                    <button type="button" class="cursor-pointer text-primary hover:text-primary-hover"
                            wire:click="decreaseQuantity" wire:loading.attr="disabled" wire:loading.class="cursor-default">
                        <x-vc::ico.minus />
                    </button>
                    <div class="mx-2.5">В корзине ({{ $quantity }})</div>
                    <button type="button" class="cursor-pointer text-primary hover:text-primary-hover"
                            wire:click="increaseQuantity" wire:loading.attr="disabled" wire:loading.class="cursor-default">
                        <x-vc::ico.plus />
                    </button>
                </div>
            @endif
        </div>
        <x-tt::notifications.success prefix="addToCart-" />
        <x-tt::notifications.error prefix="addToCart-" />
    @endif
</div>
