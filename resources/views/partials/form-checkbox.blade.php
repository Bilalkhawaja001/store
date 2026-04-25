@props(['name', 'label', 'checked' => false])

<label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500">
    <span>{{ $label }}</span>
</label>
