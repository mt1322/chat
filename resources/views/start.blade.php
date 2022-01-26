<?php
    use App\Models\Message;
    use App\Models\Message2;
    // setcookie('channel', 0);
    // setcookie('channelNum', 1);
    // file_put_contents('channel_num.txt', 0);
    // $_SESSION['channel'] = 0;
    // $_SESSION['channelNum'] = 1;
    session(['channel' => 0]);
    session(['channelNum' => 1]);
    session(['channelList' => array(0, 1)]);
    // session(['channelPostList' => array(new Message(), new Message2())]);
?>

<x-layout>
    <x-slot name="title">
        start
    </x-slot>

    <h1> Chat </h1>

    <a href="{{ route('index') }}"> start </a>
</x-layout>
