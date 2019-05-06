<?php

function show($status, $msg='', $data=[]){
    return [
        'status' => intval($status),
        'msg' => $msg,
        'data' => $data
    ];
}
