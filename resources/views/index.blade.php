<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <div class="chat-header">
        <h1> Chat </h1>
        <h2 class="channelName"> {{ session('channelName')[$channel] }} </h2>
        {{-- 表示中のチャンネルを削除する --}}
        <form method="post" action="{{ route('destroyOwnChannel', $channel) }}" class="delete-own">
            @method('DELETE')
            @csrf
            <button class="delete-btn"> チャンネル削除 </button>
        </form>

        {{-- チャンネル追加ボタン --}}
        <form method="post" action="{{ route('add') }}">
            @csrf
            <input type="text" name="newChannel">

            <button type="submit">チャンネル作成</button>
        </form>
        <br>

        {{-- 画像アップロードボタン --}}
        <form method="post" action="{{ route('upload_image') }}" enctype="multipart/form-data">
            @csrf
	        <input type="file" name="image" accept="image/png, image/jpeg">/>
	        <input type="submit" value="Upload">
        </form>
        {{-- バリデーションエラーの表示 --}}
        @error('newChannel')
            <span class="error"> {{ $message }} </span>
        @enderror
        @error('body')
            <span class="error"> {{ $message }} </span>
        @enderror

        {{-- チャンネル切り替え用タブ --}}
        <div class="channelTabss">
        @for ($i = 0; $i < $channelNum; $i++)
            @if ($i !== $channel)
            <div class="channelTabs">
                <a href="{{ route('change', $i) }}" class="channelTab"> {{ session('channelName')[$i] }} </a>
                <form method="post" action="{{ route('destroyChannel', $i) }}" class="delete-channel"> {{-- チャンネル削除 --}}
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
                @if ($add_message && $key === count($postData)-1) {{-- メッセージが送信された時に一番下のメッセージのみアニメーションを実行 --}}
                    <li class="message-list new-message">
                @else
                    <li class="message-list">
                @endif

                    @if ($editNum-1 === $key) {{-- 編集対象のメッセージに textarea を適用 --}}
                        <span class="iconFrame"> <img src="storage/{{ $upload_image }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody2">: {{ $post->body }} </span>
                        <form method="post" action="{{ route('update', $post) }}">
                            @method('PATCH')
                            @csrf
                            <textarea id="editArea" name="body" autofocus> {{ $post->body }} </textarea>
                            <script>
                                let client_h = document.getElementById('postBody2').clientHeight;
                                document.getElementById('editArea').style.height = client_h;        //textarea の高さが変わらないように調整
                            </script>
                            <button type="submit" class="edit edit-btn">更新</button>
                        </form>
                    @else
                        <span class="iconFrame"> <img src="storage/{{ $upload_image }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody">: {{ $post->body }} </span>
                        <a href="{{ route('edit', $key) }}" class="edit"> edit </a>
                    @endif

                    {{-- メッセージ削除 --}}
                    <form method="post" action="{{ route('destroy', $post) }}" class="delete-form">
                        @method('DELETE')
                        @csrf
                        <button class="delete-btn"> 削除 </button>
                    </form>
                    <span class="userName"> {{ $post->user }} </span>
                    </li>
            @empty {{-- メッセージが何も無い時 --}}
                <h2> Not found </h2>
            @endforelse
        </ul>

    </div>

    {{-- メッセージ送信用エリア --}}
    <div class="chat-footer">
        <form method="post" action="{{ route('store') }}">
            @csrf
            <textarea class="submitForm" name="body" value="{{ old('', '入力してください') }}"> </textarea>

            <button class="submitForm-btn" type="submit">送信</button>
        </form>
    </div>

    <script>
        let container = document.getElementById('chat-main');
        container.scrollIntoView(false);                            //メッセージが追加されたら自動的に最下部にスクロール

        document.querySelectorAll('.delete-form').forEach(form => { //メッセージを削除する際にポップアップを表示
            form.addEventListener('submit', e => {
                e.preventDefault();

                if (!confirm('削除しますか？')) {
                    return;
                }

                form.submit();
            });
        });
    </script>

</x-layout>

