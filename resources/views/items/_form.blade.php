<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-input-label for="item_code" value="Item Code" />
        <input id="item_code" name="item_code" type="text" class="input" value="{{ old('item_code', $item->item_code ?? '') }}">
        <x-input-error :messages="$errors->get('item_code')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" value="Item Name" />
        <input id="name" name="name" type="text" class="input" value="{{ old('name', $item->name ?? '') }}" required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="category_id" value="Category" />
        <select id="category_id" name="category_id" class="select" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit" />
        <input id="unit" name="unit" type="text" class="input" value="{{ old('unit', $item->unit ?? '') }}" required>
        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="minimum_stock_level" value="Minimum Stock Level" />
        <input id="minimum_stock_level" name="minimum_stock_level" type="number" step="0.01" class="input" value="{{ old('minimum_stock_level', $item->minimum_stock_level ?? 0) }}" required>
        <x-input-error :messages="$errors->get('minimum_stock_level')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" class="textarea" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
    <div class="md:col-span-2"><x-partials.form-checkbox name="is_active" label="Active item" :checked="old('is_active', $item->is_active ?? true)" /></div>
</div>
<div class="mt-6 flex gap-3"><button class="btn-primary">Save</button><a href="{{ route('items.index') }}" class="btn-secondary">Cancel</a></div>
