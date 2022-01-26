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

    public static function findData($id, $messageId) {
        $dataList = array(Message::find($messageId), Message2::find($messageId));

        return $dataList[$id];
    }

    public static function setData($id) {
        $dataList = array(new Message(), new Message2());

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
        // $id = file_get_contents('channel_num.txt');
        // $chanelNum = filter_input(INPUT_COOKIE, 'channelNum');
        $channel = session('channel');
        $channelNum = session('channelNum');

        // print_r(session('channelList'));

        // $posts = Message::all();
        // $posts = MessageData::getData($channel);
        $posts = MessageData::getData(session('channelList')[$channel]);
        $add_msg = session('post');
        $editNum = session('edit');

        // $str_chk = file_get_contents('message_num.txt');
        // if ($str_chk === 'add') {
        //     $add_msg = true;
        // }
        // else if (is_numeric($str_chk))
        //     $editNum = $str_chk;

        session(['post' => false]);
        session(['edit' => 0]);

        return view('index')
            ->with(['channel' => $channel, 'channelNum' => $channelNum, 'postData' => $posts, 'add_message' => $add_msg, 'editNum' => $editNum]);
    }

    public function store(Request $request){
        // $message = new Message();
        $channel = session('channel');
        $message = MessageData::setData(session('channelList')[$channel]);
        $message->user = $request->user;
        $message->body = $request->body;
        $message->save();

        // file_put_contents('message_num.txt', 'add');
        session(['post' => true]);

        return redirect()
            ->route('index');
    }

    public function edit(Int $num){
        // $getNum = explode(",", $num);
        // file_put_contents('message_num.txt', $num+1);
        session(['edit' => $num+1]);

        return redirect()
            ->route('index');
    }

    public function update(Request $request, Int $messageId){
        // $getNum = explode(",", $message);
        // $messages = Message::find($getNum[0]);
        // $messages->body = $request->body;
        // $messages->save();
        $message = MessageData::findData(session('channel'), $messageId);
        $message->body = $request->body;
        $message->save();


        return redirect()
            ->route('index');
    }

    public function destroy(Int $messageId){
        // $getNum = explode(",", $message);
        // $messages = Message::find($getNum[0]);
        // $messages->delete();
        $message = MessageData::findData(session('channel'), $messageId);
        $message->delete();

        return redirect()
            ->route('index');
    }

    public function add(){
        // $num = file_get_contents('channel_num.txt');
        // file_put_contents('channel_num.txt', $num+1);
        // $channelNum = filter_input(INPUT_COOKIE, 'channelNum');
        // setcookie('channelNum', $channelNum+1);
        session(['channelNum' => session('channelNum')+1]);

        return redirect()
            ->route('index');
    }

    public function change(Int $id){
        // $channel = filter_input(INPUT_COOKIE, 'channel');
        // setcookie('channel', $channel+1);
        // file_put_contents('channel_num.txt', $id);
        session(['channel' => $id ]);


        return redirect()
            ->route('index');
    }

    public function destroyChannel(Int $channelId){
        // $posts = MessageData::getData($channelId);
        $channelList = session('channelList');
        $deleteChannel = array_splice($channelList, $channelId, 1)[0];
        array_push($channelList, $deleteChannel);
        session(['channelList' => $channelList]);
        session(['channelNum' => session('channelNum')-1]);

        $posts = MessageData::getData($deleteChannel);
        foreach($posts as $post) {
            $post->delete();
        }

        return redirect()
            ->route('index');
    }
}
