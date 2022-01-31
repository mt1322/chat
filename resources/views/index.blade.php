<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <h1> Chat </h1>
    <h2> {{ session('channelName')[$channel] }} </h2>

    {{-- <!-- <a href="{{ route('add') }}"> addChannel </a> --> --}}
    <form method="post" action="{{ route('add') }}">
        @csrf
        <input type="text" name="newChannel">

        <button type="submit">チャンネル作成</button>
    </form>

    <br>

    @for ($i = 0; $i < $channelNum; $i++)
        @if ($i !== $channel)
            <a href="{{ route('change', $i) }}"> {{ session('channelName')[$i] }} </a>
            <form method="post" action="{{ route('destroyChannel', $i) }}" class="delete-channel">
                @method('DELETE')
                @csrf
                <button class="delete-btn"> x </button>
            </form>
        @endif
    @endfor

    <form method="post" action="{{ route('store') }}">
        @csrf
        {{-- <!-- <input type="text" name="user" value="{{ old('', '入力してください') }}"> --> --}}
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
