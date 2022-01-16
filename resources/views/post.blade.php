<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    {{-- <!--><h1> {{ $postData }} </h1><--> --}}
    <ul>
        @forelse ($postData as $post)
            <li>{{ $post->user }} : {{$post->body }}</li>
        @empty
            <h2> Not found </h2>
        @endforelse

    </ul>



</x-layout>
