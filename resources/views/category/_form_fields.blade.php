<div class="mb-4">
    <x-jet-label for="name" :value="__('validation.attributes.name')" required/>

    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $category->name)" required/>

    @error('name')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>
