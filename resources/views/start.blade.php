<?php
    // setcookie('channel', 0);
    // setcookie('channelNum', 1);
    // file_put_contents('channel_num.txt', 0);
    // $_SESSION['channel'] = 0;
    // $_SESSION['channelNum'] = 1;
    use App\Models\Store;

    $channelStore = Store::find(1);
    session(['channel' => 0]);
    session(['channelNum' => $channelStore->channelNum]);
    session(['channelName' => explode(",", $channelStore->channelName)]);
    session(['channelList' => explode(",", $channelStore->channelList)]);
    // session(['channelPostList' => array(new Message(), new Message2())]);
?>

<x-layout>
    <x-slot name="title">
        start
    </x-slot>

    <h1> Chat </h1>

    @if (session('channelNum') == 0)
        <form method="post" action="{{ route('add') }}">
            @csrf
            please input channel Name...
            <input type="text" name="newChannel">

            <button type="submit">開始</button>
        </form>
    @else
        <a href="{{ route('index') }}"> starting chat </a>
    @endif
</x-layout>
