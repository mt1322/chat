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
    /* 指定された Message の全データを取得する */
    public static function getData($id) {
        $dataList = array(Message::all(),
                          Message2::all(),
                          Message3::all(),
                          Message4::all(),
                          Message5::all());

        return $dataList[$id];
    }

    /* 指定された Message の messageId 番目のデータを取得する */
    public static function findData($id, $messageId) {
        $dataList = array(Message::find($messageId),
                          Message2::find($messageId),
                          Message3::find($messageId),
                          Message4::find($messageId),
                          Message5::find($messageId));

        return $dataList[$id];
    }

    /* 指定された空の Message を取得する */
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

    /* 通常表示 */
    public function index() {
        $channel = session('channel');                      //表示するチャンネル
        $channelNum = session('channelNum');                //チャンネルの個数
        $channelName = session('channelName');              //チャンネル名

        $stores = Store::find(1);
        $strName = implode(",", session('channelName'));    //配列をカンマ区切りの文字列に変換してデータベースに登録
        $strList = implode(",", session('channelList'));
        $stores->channelNum = $channelNum;
        $stores->channelName = $strName;
        $stores->channelList = $strList;
        $stores->save();

        if ($channelNum == 0)                               //チャンネルが削除されて0個になった場合 start に遷移
            return view('start');

        $posts = MessageData::getData(session('channelList')[$channel]);
        $editNum = session('edit');                         //何番目のメッセージを編集しているか
        $messageFlag = session('flag');                     //メッセージ表示用フラグ
        session(['edit' => 0]);
        session(['flag' => 'null']);

        $image = UploadImage::find(1);
        $imagePath = $image->file_path;                     //アップロードされた画像のパスを読み出す

        return view('index')
            ->with(['channel' => $channel,
                    'channelNum' => $channelNum,
                    'channelName' => $channelName,
                    'postData' => $posts,
                    'flag' => $messageFlag,
                    'editNum' => $editNum,
                    'upload_image' => $imagePath]
                );
    }

    /* 送信されたメッセージの追加 */
    public function store(PostRequest $request){
        $channel = session('channel');
        $message = MessageData::setData(session('channelList')[$channel]);
        $message->user = Auth::user()->name;                //ユーザ名を取り出す
        $message->body = $request->body;
        $message->save();

        session(['flag' => 'store']);                       //アニメーション実行

        return redirect()
            ->route('index');
    }

    /* 一部メッセージのみ編集できるようにするための メッセージid の特定 */
    public function edit(Int $num){
        session(['edit' => $num+1]);                        //何番目のメッセージを編集しているか

        return redirect()
            ->route('index');
    }

    /* メッセージの編集 */
    public function update(PostRequest $request, Int $messageId){
        $channel = session('channel');
        $message = MessageData::findData(session('channelList')[$channel], $messageId);
        $message->body = $request->body;
        $message->save();

        session(['flag' => 'update']);                       //アニメーション実行

        return redirect()
            ->route('index');
    }

    /* メッセージの削除 */
    public function destroy(Int $messageId){
        $channel = session('channel');
        $message = MessageData::findData(session('channelList')[$channel], $messageId);

        $message->delete();

        session(['flag' => 'delete']);                       //アニメーション実行

        return redirect()
            ->route('index');
    }

    /* チャンネルの追加 */
    public function add(ChannelRequest $request){
        if (session('channelNum') == 0)                     //チャンネルが一つも無い場合、空の配列で初期化
            session(['channelName' => array()]);

        if (session('channelNum') < 5)                      //チャンネル数が最大でない場合、チャンネル数を増やす
            session(['channelNum' => session('channelNum')+1]);

        $names = session('channelName');
        array_push($names, $request->newChannel);           //入力されたチャンネル名を配列の末尾に追加
        session(['channelName' => $names]);

        return redirect()
            ->route('index');
    }

    /* チャンネルの移動 */
    public function change(Int $id){
        session(['channel' => $id ]);                       //移動先のチャンネルの id に変更

        return redirect()
            ->route('index');
    }


    public function destroyChannelCommon(Int $channelId) {
        $channelList = session('channelList');
        $deleteChannel = array_splice($channelList, $channelId, 1)[0];  //指定されたチャンネルの順番を取り出す
        array_push($channelList, $deleteChannel);                       //削除するチャンネルを配列の末尾に移動
        session(['channelList' => $channelList]);
        session(['channelNum' => session('channelNum')-1]);             //チャンネル数-1

        $names = session('channelName');
        array_splice($names, $channelId, 1);                            //チャンネル名を配列から削除
        session(['channelName' =>  $names]);

        $posts = MessageData::getData($deleteChannel);
        foreach($posts as $post) {                                      //メッセージを全件削除する
            $post->delete();
        }

        return;
    }

    /* チャンネルの削除(表示しているチャンネル以外) */
    public function destroyChannel(Int $channelId){
        $this->destroyChannelCommon($channelId);

        return redirect()
            ->route('index');
    }

    /* チャンネルの削除(表示しているチャンネル) */
    public function destroyOwnChannel(Int $channelId){
        $channel = session('channel');
        session(['channel' => 0]);                             //一番左端のタブのチャンネルに移動する(最後のチャンネルを参照している場合、エラーになるため)

        $this->destroyChannelCommon($channelId);

        return redirect()
            ->route('index');
    }
}
