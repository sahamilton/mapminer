<form wire:submit.prevent="save">
    <x-modal.dialog wire:model.defer="showEditModal">
        <x-slot name="title">Add Activity</x-slot>

        <x-slot name="content">
            <x-input.group for="title" label="Title" :error="$errors->first('editing.title')">
                <x-input.text wire:model="editing.title" id="title" />
            </x-input.group>

            <x-input.group for="amount" label="Amount" :error="$errors->first('editing.amount')">
                <x-input.money wire:model="editing.amount" id="amount" />
            </x-input.group>

            <x-input.group for="status" label="Status" :error="$errors->first('editing.status')">
                <x-input.select wire:model="editing.status" id="status">
                    
                        <option value="1">2</option>
                    
                </x-input.select>
            </x-input.group>

            <x-input.group for="date_for_editing" label="Date" :error="$errors->first('editing.date_for_editing')">
                <x-input.date wire:model="editing.date_for_editing" id="date_for_editing" />
            </x-input.group>
        </x-slot>

        <x-slot name="footer">
            <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.primary>

            <x-button.primary type="submit">Save</x-button.primary>
        </x-slot>
    </x-modal.dialog>
</form>