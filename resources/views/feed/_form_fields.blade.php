<div class="mb-4">
    <x-label for="category_id" :value="__('validation.attributes.category_id')" required/>

    <x-select id="category_id" class="block mt-1 w-full" type="url" name="category_id" :value="old('category_id', $feed->category_id)" :options="$availableCategoryOptions" required/>

    @error('category_id')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-label for="feed_url" :value="__('validation.attributes.feed_url')" required/>

    <livewire:feed-discoverer :feed="$feed"/>

    @error('feed_url')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-label for="site_url" :value="__('validation.attributes.site_url')" required/>

    <x-input id="site_url" class="block mt-1 w-full" type="url" name="site_url" :value="old('site_url', $feed->site_url)" required/>

    @error('site_url')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-label for="name" :value="__('validation.attributes.name')" required/>

    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $feed->name)" required/>

    @error('name')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>
