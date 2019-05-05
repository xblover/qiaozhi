<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Support\Facades\Session;
use App\Handlers\FilesUploadHandler;
use App\Handlers\LoggingHandler;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public $arr = array('code' => 0, 'msg' => '', 'info' => array());

    //
    public function store(UserRequest $request, LoggingHandler $logger)
    {

        //验证验证码
        $this->verificationCode();

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => sha1($request->password),
            'code' => str_random(32)
        ]);
        //写入日志
        $logger->logging( date('Y-m-d H:i:s', time()) . '_' . $request->name . '_' . $request->phone . '_' . '注册用户');

        return response()->json([
            'status' => 201,
            'message' => '创建成功！'
        ]);
    }

    //验证验证码
    public function verificationCode()
    {
        return true;
    }

    public function login(Request $request, LoggingHandler $logger)
    {

        $this->validate($request, [
            'password' => 'required|string|min:6',
            'phone' => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/',
            ]
        ]);

        $user = User::where('phone', $request->phone)
                    ->where('password', sha1($request->password))
                    ->first();
        if (empty($user))
        {
            return response()->json([
                'status' => 400,
                'message' => '用户名或密码错误！'
            ]);
        }

        Session::put('user-code', $user->code);
        //写入日志
        $logger->logging( date('Y-m-d H:i:s', time()) . '_' . $request->phone . '_' . '登陆系统');

        return response()->json([
            'status' => 200,
            'message' => '登陆成功！'
        ]);
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required',
            'token' => 'required',
            'subject' => 'required',
            'works_base64' => 'required',
        ], [
            'member_id.required' => '用户id是必须的',
            'token.required' => '用户token是必须的',
            'subject.required' => '学科是必须的',
            'works_base64.required' => '作业图片是必须的',
        ]);

        //验证token
//        is_token(@$_POST['member_id'],@$_POST['token']);
//        $model = DB::table('order_works');
//        $order = DB::table('order');
        $member_id = @$_POST['member_id'];
        $order_id=@$_POST['order_id'];
        $subject = @$_POST['subject'];
        $works_list = json_decode(@$_POST['works_base64'],true);
        $grade = "5";//从数据库里取年级，现在设为虚值；
        $works_num=count($works_list);
//        $order_info=$order->where(array('order_id'=>$order_id))->first();
//        $order_info=(array)$order_info;

        $pic_name = $member_id . '-' . time() . '-' . $subject . '-' . $grade . '-' . '0';
        $ok_num=0;
        foreach($works_list as $k=>$v)
        {
            $data['pic_1']='';
            if(!empty(@$v['pic_1']))
            {
                $data['pic_1'] = upload_base64(@$v['pic_1'],'works',$member_id,$pic_name);
            }

//            $data['order_id']=$order_id;
//            $data['member_id']=$member_id;
////            $data['coach_id']=$order_info['coach_id'];
////            $data['teacher_id']=$order_info['teacher_id'];
//            $data['add_time']=time();
//            $data['add_date']=date("Y-m-d H:i:s",time());
//
//            $result=$model->insertGetId($data);
//            if ($result) {
//                $works_id[]=$result;
//                $ok_num++;
//            }
        }
        if($data['pic_1'])
        {
            $this->arr['msg']='上传成功！';
            $this->arr['info']=$data['pic_1'];
            echo json_encode($this->arr);exit;
        } else {
            $this->arr['code']='101';
            $this->arr['msg']='上传失败！';
            echo json_encode($this->arr);exit;
        }

//        if ($ok_num==$order_info['order_num']) {
//            $order->where(array('order_id'=>$order_id))->
//            update(array('works_upload'=>1,'works_id'=>implode(",",$works_id),'order_state'=>20));
//
//            $this->arr['msg']='上传成功！';
//            $this->arr['info']=$result;
//            echo json_encode($this->arr);exit;
//        } else {
//            $this->arr['code']='101';
//            $this->arr['msg']='上传失败！';
//            echo json_encode($this->arr);exit;
//        }

    }


    public function upload1(Request $request, FilesUploadHandler $uploader, LoggingHandler $logger)
    {
        $user_code = Session::get('user-code');
        $user = User::where('code', $user_code)
            ->first();
        if (empty($user))
        {
            return response()->json([
                'status' => 400,
                'message' => '用户验证有误！'
            ]);
        }

        $data = $request->all();

        $this->validate($request, [
            'task_name' => 'required',
            'ed' => 'required|max:100000',
            'sxxmb' => 'required|max:100000',
            'wjb' => 'required|max:100000',
            'ad' => 'required|max:100000',
        ], [
            'task_name.required' => '任务名是必须的',
            'ed.required' => '试题评分数据表是必须的',
            'ed.max' => '试题评分数据表是不能大于100',
            'sxxmb.required' => '双向细目表是必须的',
            'sxxmb.max' => '双向细目表是不能大于100',
            'wjb.required' => '问卷定义表是必须的',
            'wjb.max' => '问卷定义表是不能大于100',
            'ad.required' => '调查问卷数据表是必须的',
            'ad.max' => '调查问卷数据表是不能大于100',
        ]);

        //ed试题评分数据表,sxxmb双向细目表,wjb问卷定义表, ad调查问卷数据表
        $arr = ['ed','sxxmb','wjb','ad'];

        if ($data['mail']){
            Mail::send('emails.test',['name'=>''],function ($message) use ($request,$arr){
                $to = 'xb0004@kksnail.com';
                $message->to($to)->subject('上传文件');
                // 在邮件中上传附件
                foreach ($arr as $item) {
                    $message->attach($request->$item->path(),[
                        'as' => strtoupper($item).'.csv',
                        'mime' => 'application/csv',
                    ]);
                }
            });

            return response()->json([
                'status' => 200,
                'message' => '数据表上传成功！'
            ]);
        }

        //文件名
        $folder = $request->task_name . '_' . date('Y-m-d H:i', time()) . '_' . str_random(4);

        foreach ($arr as $item) {
            if ($request->$item)
            {
                $result = $uploader->save($request->$item, $folder,strtoupper($item));
                //写入日志
//                $logger->logging($request->task_name . '_' . date('Y-m-d H:i:s', time()) . '写入文件' . $request->$item->getClientOriginalName());

                if (!empty($result['path']))
                {
                    $data[$item] = $result['path'];
                }
                else
                {
                    return response()->json([
                        'status' => 400,
                        'message' => $result['message']
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => '数据表上传成功！'
        ]);

    }



}
