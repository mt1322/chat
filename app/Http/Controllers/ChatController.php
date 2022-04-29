<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Message2;
use App\Models\Message3;
use App\Models\Message4;
use App\Models\Message5;
use App\Models\Store;
use App\Models\User;
use App\Models\UploadImage;
use App\Http\Requests\PostRequest;
use App\Http\Requests\ChannelRequest;

class MessageData
{
    public static function getData($id) {
        $dataList = array(Message::all(),
                          Message2::all(),
                          Message3::all(),
                          Message4::all(),
                          Message5::all());

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
        $dataList = array(Message::find($messageId),
                          Message2::find($messageId),
                          Message3::find($messageId),
                          Message4::find($messageId),
                          Message5::find($messageId));

        return $dataList[$id];
    }

    public static function setData($id) {
        $dataList = array(new Message(),
                          new Message2(),
                          new Message3(),
                          new Message4(),
                          new Message5());

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
        $channelName = session('channelName');

        $stores = Store::find(1);
        $strName = implode(",", session('channelName'));
        $strList = implode(",", session('channelList'));
        $stores->channelNum = $channelNum;
        $stores->channelName = $strName;
        $stores->channelList = $strList;
        $stores->save();

        if ($channelNum == 0)
            return view('start');
        // print_r(session('channelList'));

        // $posts = Message::all();
        // $posts = MessageData::getData($channel);
        $posts = MessageData::getData(session('channelList')[$channel]);
        $add_msg = session('post');
        $editNum = session('edit');

        $image = UploadImage::find(1);
        $imagePath = $image->file_path;

        // $str_chk = file_get_contents('message_num.txt');
        // if ($str_chk === 'add') {
        //     $add_msg = true;
        // }
        // else if (is_numeric($str_chk))
        //     $editNum = $str_chk;

        session(['post' => false]);
        session(['edit' => 0]);

        return view('index')
            ->with(['channel' => $channel, 'channelNum' => $channelNum, 'channelName' => $channelName, 'postData' => $posts, 'add_message' => $add_msg, 'editNum' => $editNum, 'upload_image' => $imagePath]);
    }

    public function store(PostRequest $request){
        // $message = new Message();
        // $request->validate([
        //     'body' => 'required',
        // ],
        // [
        //     'body.required' => 'メッセージを入力してください',
        // ]);

        $channel = session('channel');
        $message = MessageData::setData(session('channelList')[$channel]);
        $message->user = Auth::user()->name;
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

    public function update(PostRequest $request, Int $messageId){
        // $request->validate([
        //     'body' => 'required',
        // ],
        // [
        //     'body.required' => 'メッセージを入力してください',
        // ]);

        $channel = session('channel');
        $message = MessageData::findData(session('channelList')[$channel], $messageId);
        $message->body = $request->body;
        $message->save();


        return redirect()
            ->route('index');
    }

    public function destroy(Int $messageId){
        $channel = session('channel');
        $message = MessageData::findData(session('channelList')[$channel], $messageId);

        // $message = Message3::find($messageId);
        $message->delete();

        return redirect()
            ->route('index');
    }

    public function add(ChannelRequest $request){
        // $num = file_get_contents('channel_num.txt');
        // file_put_contents('channel_num.txt', $num+1);
        // $channelNum = filter_input(INPUT_COOKIE, 'channelNum');
        // setcookie('channelNum', $channelNum+1);
        // $request->validate([
        //     'newChannel' => 'required',
        // ],
        // [
        //     'newChannel.required' => 'チャンネル名を入力してください',
        // ]);

        if (session('channelNum') == 0)
            session(['channelName' => array()]);

        if (session('channelNum') < 5)
            session(['channelNum' => session('channelNum')+1]);

        $names = session('channelName');
        array_push($names, $request->newChannel);
        session(['channelName' => $names]);

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

    public function destroyChannelCommon(Int $channelId) {
        $channelList = session('channelList');
        $deleteChannel = array_splice($channelList, $channelId, 1)[0];
        array_push($channelList, $deleteChannel);
        session(['channelList' => $channelList]);
        session(['channelNum' => session('channelNum')-1]);

        $names = session('channelName');
        array_splice($names, $channelId, 1);
        session(['channelName' =>  $names]);

        $posts = MessageData::getData($deleteChannel);
        foreach($posts as $post) {
            $post->delete();
        }

        return;
    }

    public function destroyChannel(Int $channelId){
        $this->destroyChannelCommon($channelId);

        return redirect()
            ->route('index');
    }

    public function destroyOwnChannel(Int $channelId){
        $channel = session('channel');
        // if ($channel > 0 || $channel == 0 && session('channelNum') > 1)
        session(['channel' => 0]);

        $this->destroyChannelCommon($channelId);

        return redirect()
            ->route('index');
    }
}
