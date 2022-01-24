<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <h1> Chat </h1>

    <a href="{{ route('add') }}"> addChannel </a>
    <br>

    @for ($i = 0; $i < $channelNum; $i++)
        @if ($i !== intval($channel))
            <a href="{{ route('change', $i) }}"> channel{{ $i+1 }} </a>
        @endif
    @endfor

    <form method="post" action="{{ route('store') }}">
        @csrf
        <input type="text" name="user" value="{{ old('', '入力してください') }}">
        <textarea name="body" value="{{ old('', '入力してください') }}"> </textarea>

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
                    <?php /*$qry = $post->id . ',' . $channel;*/ ?>
                    <form method="post" action="{{ route('update', $post) }}">
                        @method('PATCH')
                        @csrf
                        {{-- <!-- <textarea name="body" class="editArea" value="{{ $post->body }}"></textarea> --> --}}
                        <input type="text" name="body" class="editArea" value="{{ old('入力してください', $post->body) }}">
                        <button type="submit">編集</button>
                    </form>
                @else
                    <span class="user">{{ $post->user }}</span> : {{ $post->body }}
                    <?php /*$qry = $key . ',' . $channel;*/ ?>
                    <a href="{{ route('edit', $key) }}"> edit </a>
                @endif


            </li>
            <?php /*$qry = $post->id . ',' . $channel;*/ ?>
            <form method="post" action="{{ route('destroy', $post) }}" class="delete-form">
                @method('DELETE')
                @csrf
                <button class="delete-btn"> x </button>
            </form>
        @empty
            <h2> Not found </h2>
        @endforelse
    </ul>

</x-layout>
