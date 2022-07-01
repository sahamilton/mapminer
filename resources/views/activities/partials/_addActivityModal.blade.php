
<form wire:submit.prevent="save">
    <x-modal.dialog wire:model.defer="showActivityModal">
        <x-slot name="title">Add Activity</x-slot>

        <x-slot name="content">
            
        </x-slot>

        <x-slot name="footer">
            <x-button.secondary wire:click="$set('showActivityModal', false)">Cancel</x-button.primary>

            <x-button.primary type="submit">Save</x-button.primary>
        </x-slot>
    </x-modal.dialog>
</form>