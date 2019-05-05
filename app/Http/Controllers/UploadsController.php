<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\FilesUploadHandler;
use App\Handlers\LoggingHandler;

class UploadsController extends Controller
{
    /**
     * 展示文件上传界面
     *
     * @return \Illuminate\Http\Response
     */
    public function showUploadForm()
    {
        return view('quality_center.upload_files');
    }

    public function upload(Request $request, FilesUploadHandler $uploader, LoggingHandler $logger)
    {
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

        $folder = $request->task_name . '_' . date('Y-m-d H:i', time()) . '_' . str_random(4);

        //ed试题评分数据表,sxxmb双向细目表,wjb问卷定义表, ad调查问卷数据表
        $arr = ['ed','sxxmb','wjb','ad'];

        foreach ($arr as $item) {
            if ($request->$item)
            {
                $result = $uploader->save($request->$item, $folder,strtoupper($item));
                //写入日志
                $logger->logging($request->task_name . '_' . date('Y-m-d H:i:s', time()) . '写入文件' . $request->$item->getClientOriginalName());

                if (!empty($result['path']))
                {
                    $data[$item] = $result['path'];
                }
                else
                {
                    return redirect()->route('upload')->with('danger', $result['message']);
                }
            }
        }

//        dd($data);
        return redirect()->route('upload')->with('success', '数据表上传成功！');
    }


}
