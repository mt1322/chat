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



        <form method="post" action="{{ route('upload_image') }}" enctype="multipart/form-data">
            @csrf
	        <input type="file" name="image" accept="image/png, image/jpeg">/>
	        <input type="submit" value="Upload">
        </form>
        @error('newChannel')
            <span class="error"> {{ $message }} </span>
        @enderror
        @error('body')
            <span class="error"> {{ $message }} </span>
        @enderror

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
    <div id="chat-main">

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
                        <span class="iconFrame"><img src="storage/{{ $upload_image }}" alt="" title="upload_image" class="icon">{{-- $post->user --}}</span> <span id="postBody2">: {{ $post->body }} </span>
                        <?php /*$qry = $post->id . ',' . $channel;*/ ?>
                        <form method="post" action="{{ route('update', $post) }}">
                            @method('PATCH')
                            @csrf
                            {{-- <!-- <textarea name="body" class="editArea" value="{{ $post->body }}"></textarea> --> --}}
                            <textarea id="editArea" name="body" autofocus> {{ $post->body }} </textarea>
                            <script>
                                let client_h = document.getElementById('postBody2').clientHeight;
                                document.getElementById('editArea').style.height = client_h;
                            </script>
                            <button type="submit" class="edit edit-btn">編集</button>
                        </form>
                    @else
                        <span class="iconFrame"><img src="/storage/{{ $upload_image }}" alt="" title="upload_image" class="icon">{{-- $post->user --}}</span> <span id="postBody">: {{ $post->body }} </span>
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

    <div class="chat-footer">
        <form method="post" action="{{ route('store') }}">
            @csrf
            {{-- <!-- <input type="text" name="user" value="{{ old('', '入力してください') }}"> --> --}}
            <textarea class="submitForm" name="body" value="{{ old('', '入力してください') }}"> </textarea>

            <button class="submitForm-btn" type="submit">送信</button>
        </form>
    </div>

    <script>
        let container = document.getElementById('chat-main');
        container.scrollIntoView(false); //メッセージが追加されたら自動的に最下部にスクロール
    </script>

</x-layout>

