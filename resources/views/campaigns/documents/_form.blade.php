@wire
    <x-form-input label="Document Title" name="document.title" />
    <x-form-textarea label="Document Description" name="document.description" />
    <x-form-input label="URL / Location" name="document.link" />
    <input type="file" wire:model="file">
    <x-form-select label="Associated Campaign" name="document.campaign_id" :options="$campaigns" />
    {{$document->type}}

@endwire