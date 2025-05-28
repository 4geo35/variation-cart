@props(["item"])
<div>
    <x-tt::h4 class="mb-1">{{ $item->product->title }}</x-tt::h4>
    <div class="text-sm text-secondary">{{ $item->variation->title }}</div>
    <div>
        <span class="text-sm text-secondary">{{ $item->quantity }} шт. x</span> {{ $item->variation->model->human_price }} руб.
    </div>
</div>
