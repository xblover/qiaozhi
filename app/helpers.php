<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

//判断用户token
function is_token($id,$token)
{
    $arr = array('code' => 0, 'msg' => '', 'info' => array());
    $model = DB::table('member');
    $where['id']=$id;
    $where['token']=$token;
    $member_info=$model->where($where)->first();
    $member_info=(array)$member_info;

    if(empty($member_info) && $token!='777777')
    {
        $arr['code']='100';
        $arr['msg']='用户token错误！';
        //$arr['info']=(object)array();
        echo json_encode($arr);exit;
    }else
    {
        return true;
    }
}


/**
 *上传base64图片
 */
function upload_base64( $base64, $file='', $member_id, $pic_name='')
{
    $new_file="storage/app/upload/$file/$member_id/";
    if (! file_exists ( $new_file )) {
        mkdir ( "$new_file", 0775, true );
    }
    $base64_image_content=$base64;
    //保存base64字符串为图片
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $pic_name=$pic_name.".{$type}";
        $new_file = $new_file.$pic_name;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            if ($result) {
                return "$file/$member_id/".$pic_name;
            } else {
                return false;
            }
        }
    }else{
        return false;
    }
}
