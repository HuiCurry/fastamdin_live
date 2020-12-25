<?php
//git webhook 自动部署脚本
$requestBody = file_get_contents("php://input");    //接收数据
if (empty($requestBody)) {              //判断数据是不是空
    die('send fail');
}
$webPath = '/www/wwwroot/zhibo.yangyonghui.com';
$content = json_decode($requestBody, true);     //数据转换
file_put_contents($webPath.'/git_web_hook.log', json_encode($content), FILE_APPEND);//将每次拉取信息追加写入到日志里
//若是主分支且提交数大于0
if ($content['ref']=='refs/heads/main') {

    //或将命令加入 shell里，看个人需求 ss git reset --hard origin/master && git clean -f
    $res = shell_exec('cd '.$webPath.' && echo `sudo git pull` >> '.$webPath.'/git_web_hook.log');//PHP函数执行git命令
    $res_log = '-------------------------'.PHP_EOL;
    $res_log .= ' 在' . date('Y-m-d H:i:s') . '向' . $content['repository']['name'] . '项目的' . $content['ref'] . '分支push'.$res;
    file_put_contents($webPath.'/git_web_hook.log', $res_log, FILE_APPEND);//将每次拉取信息追加写入到日志里
}