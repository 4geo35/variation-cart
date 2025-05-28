<div class="space-y-indent-half">
    <div class="flex flex-wrap items-center justify-start">
        <div class="flex items-center justify-start mr-indent-half mb-indent-half">
            <button type="button" class="btn btn-outline-secondary rounded-e-none border-e-0"
                    wire:click="decreaseQuantity"
                    @if ($quantity <= 1) disabled @endif>
                <x-vc::ico.minus />
            </button>
            <input type="number" aria-label="Количество" class="form-control border-secondary text-center rounded-none max-w-24 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" wire:model.live="quantity" min="1">
            <button type="button" class="btn btn-outline-secondary rounded-s-none border-s-0"
                    wire:click="increaseQuantity">
                <x-vc::ico.plus />
            </button>
        </div>
        <button type="button" class="btn btn-primary mb-indent-half"
                wire:click="addToCart" @if (!$variationId) disabled @endif>
            Добавить в корзину
        </button>
    </div>
    <x-tt::notifications.success prefix="addToCart-" />
    <x-tt::notifications.error prefix="addToCart-" />
</div>
