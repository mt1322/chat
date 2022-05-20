<x-layout>
    <x-slot name="title">
        chat
    </x-slot>

    <div class="chat-header">
        {{-- メッセージの表示 --}}
        @if ($flag === 'store')
            <p class="popup">送信完了</p>
        @elseif ($flag === 'update')
            <p class="popup">更新完了</p>
        @elseif ($flag === 'delete')
            <p class="popup">削除完了</p>
        @endif
        {{-- バリデーションエラーの表示 --}}
        @error('newChannel')
            <p class="popup"> {{ $message }} </p>
        @enderror
        @error('body')
            <p class="popup"> {{ $message }} </p>
        @enderror
        <h1> Chat </h1>
        <h2 class="channelName"> {{ session('channelName')[$channel] }} </h2>
        {{-- 表示中のチャンネルを削除する --}}
        <form method="post" action="{{ route('destroyOwnChannel', $channel) }}" class="delete-own">
            @method('DELETE')
            @csrf
            <button class="delete-btn"> チャンネル削除 </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                <button class="logout">{{ __('Log Out') }}</button>
            </x-responsive-nav-link>
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
                <div class="time-label">
                    <span class="created"> {{ str_replace('-', '/', $post->created_at) }} </span>
                    @if ($post->created_at != $post->updated_at) {{-- 編集した場合のみ表示 --}}
                        <span class="created"> 編集済み </span>
                    @endif
                </div>
                @if ($flag === 'store' && $key === count($postData)-1) {{-- メッセージが送信された時に一番下のメッセージのみアニメーションを実行 --}}
                    <li class="message-list new-message">
                @elseif ($login_user === $post->user)
                    <li class="message-list own-message">
                @else
                    <li class="message-list">
                @endif

                    @if ($editNum-1 === $key) {{-- 編集対象のメッセージに textarea を適用 --}}
                        @if (array_key_exists($post->user, $upload_image)) {{-- 既に画像ファイルがアップロードされている場合 --}}
                            <span class="iconFrame"> <img src="storage/{{ $upload_image[$post->user] }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody2">: {{ $post->body }} </span>
                        @else
                            <span class="iconFrame"> <img src="storage/{{ $upload_image["default"] }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody2">: {{ $post->body }} </span>
                        @endif
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
                        @if (array_key_exists($post->user, $upload_image)) {{-- 既に画像ファイルがアップロードされている場合 --}}
                            <span class="iconFrame"> <img src="storage/{{ $upload_image[$post->user] }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody">: {{ $post->body }} </span>
                        @else
                            <span class="iconFrame"> <img src="storage/{{ $upload_image["default"] }}" alt="" title="upload_image" class="icon"> </span> <span id="postBody">: {{ $post->body }} </span>
                        @endif
                        @if ($login_user === $post->user) {{-- 自分以外のユーザのメッセージを編集できないようにする --}}
                            <a href="{{ route('edit', $key) }}" class="edit"> edit </a>
                        @endif
                    @endif

                    {{-- メッセージ削除 --}}
                    @if ($login_user === $post->user) {{-- 自分以外のユーザのメッセージを削除できないようにする --}}
                       <form method="post" action="{{ route('destroy', $post) }}" class="delete-form">
                            @method('DELETE')
                            @csrf
                            <button class="delete-btn"> 削除 </button>
                        </form>
                    @endif
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
            <div class="footerForm">
                <textarea class="submitForm" name="body" value="{{ old('', '入力してください') }}"> </textarea>
                <button class="submitForm-btn" type="submit">送信</button>
            </div>
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

