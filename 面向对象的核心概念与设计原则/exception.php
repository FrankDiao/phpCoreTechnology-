<?php

/**
 * @param $errno int 错误级别
 * @param $errstr string 错误信息
 * @param $errfile string 错误文件
 * @param $errline int 错误行号
 * @throws Exception
 */
function error_handler($errno,$errstr,$errfile,$errline){
    throw new Exception($errstr);
}

set_error_handler("error_handler");

class test {
    public function main($num){
        $n = 0;
        try{
            $n = $num/0;
        }catch (Exception $e){
            print_r($e->getFile());
            print_r($e->getLine());
            print_r($e->getMessage());
            die;
        }

        return $n;
    }
}

$t = new test();
echo $t->main(2);