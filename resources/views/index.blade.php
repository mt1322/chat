<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <h1> Chat </h1>

    <form method="post" action="{{ route('store') }}">
        @csrf
        <input type="text" name="user" value="{{ old('', '入力してください') }}">
        <textarea name="body" value="{{ old('') }}"> </textarea>

        <button type="submit">送信</button>
    </form>

    <ul>
        @forelse ($postData as $key => $post)
            @if ($add_message && $key === count($postData)-1)
                <li class="message-list new-message">
            @else
                <li class="message-list">
            @endif
                {{-- {{ var_dump($key); }}
                {{ var_dump(count($postData)); }} --}}
                @if ($editNum-1 === $key)
                    <span class="user">{{ $post->user }}</span> :
                    <form method="post" action="{{ route('update', $post) }}">
                        @method('PATCH')
                        @csrf
                        <textarea name="body"></textarea>
                        <button type="submit">編集</button>
                    </form>
                @else
                    <span class="user">{{ $post->user }}</span> : {{ $post->body }}
                @endif
                <a href="{{ route('edit', $key) }}"> edit </a>

                <form method="post" action="{{ route('destroy', $post) }}" class="delete-form">
                    @method('DELETE')
                    @csrf
                    <button class="delete-btn"> x </button>
                </form>
            </li>
        @empty
            <h2> Not found </h2>
        @endforelse
    </ul>

</x-layout>
