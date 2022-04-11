<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <!--<div class="scroll">
        <div id="scroll-inner">-->

    <div class="chat-header">
        <h1> Chat </h1>
        <h2 class="channelName"> {{ session('channelName')[$channel] }} </h2>
        <form method="post" action="{{ route('destroyOwnChannel', $channel) }}" class="delete-own">
            @method('DELETE')
            @csrf
            <button class="delete-btn"> チャンネル削除 </button>
        </form>

        {{-- <!-- <a href="{{ route('add') }}"> addChannel </a> --> --}}
        <form method="post" action="{{ route('add') }}">
            @csrf
            <input type="text" name="newChannel">

            <button type="submit">チャンネル作成</button>
        </form>

        <br>

        <form method="post" action="{{ route('store') }}">
            @csrf
            {{-- <!-- <input type="text" name="user" value="{{ old('', '入力してください') }}"> --> --}}
            <textarea class="submitForm" name="body" value="{{ old('', '入力してください') }}"> </textarea>

            <button class="submitForm-btn" type="submit">送信</button>
        </form>

        <form method="post" action="{{ route('upload_image') }}" enctype="multipart/form-data">
            @csrf
	        <input type="file" name="image" accept="image/png, image/jpeg">/>
	        <input type="submit" value="Upload">
        </form>

        <div class="channelTabss">
        @for ($i = 0; $i < $channelNum; $i++)
            @if ($i !== $channel)
            <div class="channelTabs">
                <a href="{{ route('change', $i) }}" class="channelTab"> {{ session('channelName')[$i] }} </a>
                <form method="post" action="{{ route('destroyChannel', $i) }}" class="delete-channel">
                    @method('DELETE')
                    @csrf
                    <button class="delete-channel"> x </button>
                </form>
            </div>
            @endif
        @endfor
        </div>

    </div>
    <div class="chat-main">

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
                        <span class="iconFrame"><img src="storage/{{ $upload_image }}" alt="" title="upload_image" class="icon">{{-- $post->user --}}</span> <span class="postBody">:  </span>
                        <?php /*$qry = $post->id . ',' . $channel;*/ ?>
                        <form method="post" action="{{ route('update', $post) }}">
                            @method('PATCH')
                            @csrf
                            {{-- <!-- <textarea name="body" class="editArea" value="{{ $post->body }}"></textarea> --> --}}
                            <input type="text" name="body" class="editArea" value="{{ old('入力してください', $post->body) }}">
                            <button type="submit" class="edit edit-btn">編集</button>
                        </form>
                    @else
                        <span class="iconFrame"><img src="/storage/{{ $upload_image }}" alt="" title="upload_image" class="icon">{{-- $post->user --}}</span> <span class="postBody">: {{ $post->body }} </span>
                        <?php /*$qry = $key . ',' . $channel;*/ ?>
                        <a href="{{ route('edit', $key) }}" class="edit"> edit </a>
                    @endif

                    <?php /*$qry = $post->id . ',' . $channel;*/ ?>
                    <form method="post" action="{{ route('destroy', $post) }}" class="delete-form">
                        @method('DELETE')
                        @csrf
                        <button class="delete-btn"> 削除 </button>
                    </form>
                    <span class="userName"> {{ $post->user }} </span>
                    </li>
            @empty
                <h2> Not found </h2>
            @endforelse
        </ul>

    </div>

{{-- </div>
</div> --}}

    {{-- <script>
        let target = document.getElementById('scroll-inner');
        target.scrollIntoView(false);
    </script> --}}

</x-layout>

