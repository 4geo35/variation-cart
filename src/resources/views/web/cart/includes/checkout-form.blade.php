<div class="bg-white rounded-base p-indent xl:px-indent-double mb-indent">
    <form wire:submit.prevent="store" id="checkoutForm" class="space-y-indent">
        <x-tt::notifications.error />
        <x-tt::notifications.success />

        <div>
            <input type="text" id="name" placeholder="Имя*"
                   class="form-control {{ $errors->has("name") ? "border-danger" : "" }}"
                   required
                   wire:loading.attr="disabled"
                   wire:model="name">
            <x-tt::form.error name="name"/>
        </div>

        <div>

            <input type="text" id="email" placeholder="Email"
                   class="form-control {{ $errors->has("email") ? "border-danger" : "" }}"
                   wire:loading.attr="disabled"
                   wire:model="email">
            <x-tt::form.error name="email"/>
        </div>

        <div>
            <input type="text" id="phone" placeholder="Номер телефона"
                   class="form-control {{ $errors->has("phone") ? "border-danger" : "" }}"
                   wire:loading.attr="disabled"
                   wire:model="phone">
            <x-tt::form.error name="phone"/>
        </div>

        <div>
            <textarea id="singleDescription" class="form-control !min-h-20" placeholder="Комментарий" wire:model="comment"></textarea>
        </div>

        <div>
            <div class="form-check">
                <input type="checkbox" wire:model="policy" id="singlePolicy" required
                       class="form-check-input {{ $errors->has('policy') ? 'border-danger' : '' }}"/>
                <label for="singlePolicy" class="form-check-label">
                    @include("tt::policy.check-text")
                </label>
            </div>
            <x-tt::form.error name="policy"/>
        </div>
    </form>
</div>
