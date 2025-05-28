<div class="card">
    <div class="card-body">
        <form wire:submit.prevent="store" id="checkoutForm" class="space-y-indent-half">
            <x-tt::notifications.error />
            <x-tt::notifications.success />

            <div>
                <label for="name" class="inline-block mb-2">
                    Имя<span class="text-danger">*</span>
                </label>
                <input type="text" id="name"
                       class="form-control {{ $errors->has("name") ? "border-danger" : "" }}"
                       required
                       wire:loading.attr="disabled"
                       wire:model="name">
                <x-tt::form.error name="name"/>
            </div>

            <div>
                <label for="email" class="inline-block mb-2">
                    Email
                </label>
                <input type="text" id="email"
                       class="form-control {{ $errors->has("email") ? "border-danger" : "" }}"
                       wire:loading.attr="disabled"
                       wire:model="email">
                <x-tt::form.error name="email"/>
            </div>

            <div>
                <label for="phone" class="inline-block mb-2">
                    Phone
                </label>
                <input type="text" id="phone"
                       class="form-control {{ $errors->has("phone") ? "border-danger" : "" }}"
                       wire:loading.attr="disabled"
                       wire:model="phone">
                <x-tt::form.error name="phone"/>
            </div>

            <div>
                <label for="singleDescription" class="inline-block mb-2">
                    Комментарий
                </label>
                <textarea id="singleDescription" class="form-control !min-h-20" wire:model="comment"></textarea>
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
</div>
