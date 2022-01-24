<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Message2;

class MessageData
{
    public static function getData($id) {
        $dataList = array(Message::all(), Message2::all());

        // switch($id) {
        //     case 0:
        //         $data = Message::all();
        //         break;
        //     case 1:
        //         $data = Message2::all();
        //         break;
        //     default:
        //         break;
        // }
        return $dataList[$id];
    }
}

class ChatController extends Controller
{
    public function start() {


        return view('start');
    }

    public function index() {
        // $id = filter_input(INPUT_COOKIE, 'channel');
        $id = file_get_contents('channel_num.txt');
        $chanelNum = filter_input(INPUT_COOKIE, 'channelNum');


        // $posts = Message::all();
        $posts = MessageData::getData($id);
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
            ->with(['channel' => $id, 'channelNum' => $chanelNum, 'postData' => $posts, 'add_message' => $add_msg, 'editNum' => $editNum]);
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
        // $getNum = explode(",", $num);
        file_put_contents('message_num.txt', $num+1);

        return redirect()
            ->route('index');
    }

    public function update(Request $request, Message $message){
        // $getNum = explode(",", $message);
        // $messages = Message::find($getNum[0]);
        // $messages->body = $request->body;
        // $messages->save();
        $message->body = $request->body;
        $message->save();


        return redirect()
            ->route('index');
    }

    public function destroy(Message $message){
        // $getNum = explode(",", $message);
        // $messages = Message::find($getNum[0]);
        // $messages->delete();
        $message->delete();

        return redirect()
            ->route('index');
    }

    public function add(){
        // $num = file_get_contents('channel_num.txt');
        // file_put_contents('channel_num.txt', $num+1);
        $channelNum = filter_input(INPUT_COOKIE, 'channelNum');
        setcookie('channelNum', $channelNum+1);

        return redirect()
            ->route('index');
    }

    public function change(Int $id){
        $channel = filter_input(INPUT_COOKIE, 'channel');
        // setcookie('channel', $channel+1);
        file_put_contents('channel_num.txt', $id);

        return redirect()
            ->route('index');
    }
}
