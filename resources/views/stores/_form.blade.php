<div class="grid gap-6 md:grid-cols-2">
    <div><x-input-label for="store_code" value="Store Code" /><input id="store_code" name="store_code" type="text" class="input" value="{{ old('store_code', $store->store_code ?? '') }}"><x-input-error :messages="$errors->get('store_code')" class="mt-2" /></div>
    <div><x-input-label for="name" value="Store Name" /><input id="name" name="name" type="text" class="input" value="{{ old('name', $store->name ?? '') }}" required><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
    <div><x-input-label for="location" value="Location" /><input id="location" name="location" type="text" class="input" value="{{ old('location', $store->location ?? '') }}"><x-input-error :messages="$errors->get('location')" class="mt-2" /></div>
    <div><x-input-label for="incharge_name" value="Incharge Name" /><input id="incharge_name" name="incharge_name" type="text" class="input" value="{{ old('incharge_name', $store->incharge_name ?? '') }}"><x-input-error :messages="$errors->get('incharge_name')" class="mt-2" /></div>
    <div><x-input-label for="contact_no" value="Contact No" /><input id="contact_no" name="contact_no" type="text" class="input" value="{{ old('contact_no', $store->contact_no ?? '') }}"><x-input-error :messages="$errors->get('contact_no')" class="mt-2" /></div>
    <div class="md:col-span-2"><x-input-label for="remarks" value="Remarks" /><textarea id="remarks" name="remarks" class="textarea" rows="4">{{ old('remarks', $store->remarks ?? '') }}</textarea><x-input-error :messages="$errors->get('remarks')" class="mt-2" /></div>
    <div class="md:col-span-2"><x-partials.form-checkbox name="is_active" label="Active store" :checked="old('is_active', $store->is_active ?? true)" /></div>
</div>
<div class="mt-6 flex gap-3"><button class="btn-primary">Save</button><a href="{{ route('stores.index') }}" class="btn-secondary">Cancel</a></div>
