<div class="mb-4">
    <x-jet-label for="category_id" :value="__('validation.attributes.category_id')" required/>

    <x-input.select id="category_id" class="block mt-1 w-full" type="url" name="category_id" :value="old('category_id', $feed->category_id)" :options="$availableCategoryOptions" required/>

    @error('category_id')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-jet-label for="feed_url" :value="__('validation.attributes.feed_url')" required/>

    <livewire:feed-discoverer :feed="$feed" site-url-input-element-selector="#site_url" name-input-element-selector="#name"/>

    @error('feed_url')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-jet-label for="site_url" :value="__('validation.attributes.site_url')" required/>

    <x-jet-input id="site_url" class="block mt-1 w-full" type="url" name="site_url" :value="old('site_url', $feed->site_url)" required/>

    @error('site_url')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>

<div class="mb-4">
    <x-jet-label for="name" :value="__('validation.attributes.name')" required/>

    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $feed->name)" required/>

    @error('name')
        <x-validation-error>{{ $message }}</x-validation-error>
    @enderror
</div>
