<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageNum
{
    private $messages = 0;
}

class ChatController extends Controller
{
    public function index() {
        $posts = Message::all();
        $add_msg = false;
        $editNum = 0;

        $str_chk = file_get_contents('message_num.txt');
        if ($str_chk === 'add') {
            $add_msg = true;
        }
        else if (is_numeric($str_chk))
            $editNum = $str_chk;

        file_put_contents('message_num.txt', 'get');

        return view('index')
            ->with(['postData' => $posts, 'add_message' => $add_msg, 'editNum' => $editNum]);
    }

    public function store(Request $request){
        $message = new Message();
        $message->user = $request->user;
        $message->body = $request->body;
        $message->save();

        file_put_contents('message_num.txt', 'add');

        return redirect()
            ->route('index');
    }

    public function edit(Int $num){
        file_put_contents('message_num.txt', $num+1);

        return redirect()
            ->route('index');
    }

    public function update(Request $request, Message $message){
        // $message->body = $request->body;
        // $message->save();

        return redirect()
            ->route('index');
    }

    public function destroy(Message $message){
        $message->destroy();

        return redirect()
            ->route('index');
    }
}
