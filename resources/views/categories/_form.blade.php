<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-input-label for="name" value="Category Name" />
        <input id="name" name="name" type="text" class="input" value="{{ old('name', $category->name ?? '') }}" required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" class="textarea" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-partials.form-checkbox name="is_active" label="Active category" :checked="old('is_active', $category->is_active ?? true)" />
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="btn-primary">Save</button>
    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
</div>
