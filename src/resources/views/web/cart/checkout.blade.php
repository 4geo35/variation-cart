<x-app-layout>
    @include("vc::web.cart.includes.checkout-metas")
    @include("vc::web.cart.includes.checkout-breadcrumbs")
    @include("vc::web.cart.includes.checkout-h1")

    <livewire:vc-checkout />
</x-app-layout>
