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

        $add_chk = file_get_contents('message_num.txt');
        if ($add_chk === 'add') {
            $add_msg = true;
            file_put_contents('message_num.txt', 'get');
        }

        return view('index')
            ->with(['postData' => $posts, 'add_message' => $add_msg]);
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

    public function destroy(Message $message){
        $message->delete();

        return redirect()
            ->route('index');
    }
}
