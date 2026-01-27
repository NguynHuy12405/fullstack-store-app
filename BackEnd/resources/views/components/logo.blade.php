@php
    use App\Models\Contact;
    $contact = Contact::where('status', true)->get();
    $map = $contact->pluck('url', 'name');
@endphp

<a href="{{ route('user.home.index') }}" class="text-3xl  font-bold tracking-widest uppercase">
    {{ $map->get('NameStore', 'Không có') }}.
</a>    