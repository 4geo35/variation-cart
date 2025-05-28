<div>
    <a href="{{ route('web.cart') }}" class="hover:text-primary-hover relative">
        @if ($cartInfo->count)
            <span class="inline-block bg-primary py-0.5 px-1.5 rounded-full text-white text-xs absolute -top-2 -right-9 z-10">{{ $cartInfo->count }}</span>
        @endif
        <x-vc::ico.shopping-cart />
    </a>
</div>
