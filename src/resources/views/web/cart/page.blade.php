<x-app-layout>
    @include("vc::web.cart.includes.cart-metas")
    @include("vc::web.cart.includes.cart-breadcrumbs")
    @include("vc::web.cart.includes.cart-h1")

    <div class="container">
        @if ($cartInfo->count)
            <div class="row">
                <div class="col w-2/3">
                    <livewire:vc-cart-list />
                </div>
                <div class="col w-1/3">
                    Info
                </div>
            </div>
        @else
            <livewire:vc-cart-list />
        @endif
    </div>
</x-app-layout>
