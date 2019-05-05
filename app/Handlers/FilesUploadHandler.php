<?php

namespace App\Handlers;

use Illuminate\Support\Facades\File;

class FilesUploadHandler
{

    protected $allowed_ext = ["csv"];

    public function save($file, $folder, $file_name)
    {

        //构建存储的文件夹规则
        $folder_name = "uploads/files/$folder";

        //文件具体存储的物理路径
        $upload_path = public_path() . '/' . $folder_name;

        //获取文件的后缀名
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = $file_name . '.' . $extension;

        if (!file_exists($upload_path)) {
            $folder = File::makeDirectory($upload_path, $mode = 0755, true, true);
            if (!$folder)
            {
                return [
                    'message' => '创建文件夹失败！',
                ];
            }
        }

        if ( ! in_array($extension, $this->allowed_ext)) {
            return ['message' => '只能上传csv格式的文件'];
        }

        //移动文件到目标存储路径中
        $file->move($upload_path, $filename);

        //创建一个空的finished.txt
        $fp=fopen( $upload_path . "/finished.txt","a+");
        fclose($fp);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }
}
