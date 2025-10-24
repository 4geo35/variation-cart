@props(["item"])
<div class="space-y-2">
    <x-tt::h4>{{ $item->product->title }}</x-tt::h4>
    <div class="text-body/60">{{ $item->variation->title }}</div>
    <div>
        <span class="text-sm text-body/60">{{ $item->quantity }} шт. x</span> <span class="font-semibold">{{ $item->variation->model->human_price }} р.</span>
    </div>
</div>
