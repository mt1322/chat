<?php
    setcookie('channel', 0);
    setcookie('channelNum', 1);
    file_put_contents('channel_num.txt', 0);
?>

<x-layout>
    <x-slot name="title">
        start
    </x-slot>

    <h1> Chat </h1>

    <a href="{{ route('index') }}"> start </a>
</x-layout>
