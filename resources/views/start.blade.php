

<x-layout>
    <x-slot name="title">
        start
    </x-slot>

    <h1> Chat </h1>

        <form method="post" action="{{ route('add') }}">
            @csrf
            please input channel Name...
            <input type="text" name="newChannel">

            <button type="submit">開始</button>
        </form>
</x-layout>
