@props(['placeholder' => 'Search...', 'action' => url()->current()])

<form method="GET" action="{{ $action }}" class="mb-6 flex flex-col gap-3 md:flex-row">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $placeholder }}" class="input md:max-w-md" />
    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Search</button>
        <a href="{{ $action }}" class="btn-secondary">Reset</a>
    </div>
</form>
