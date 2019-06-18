<?php
if (isset($this->session->data['already'])){
    foreach ($modules as $module){
        if ($module['value'] == $this->session->data['already'] || $module['value'] == 'http://'.$this->session->data['already']){
            echo $module['phone1'].'<br>'.$module['phone2'];
        }
    }
}else{
    $success = false;
    foreach ($modules as $module){
        if ($success == false){
            if ($module['value'] == $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] || $module['value'] == 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']){
                echo $module['phone1'].'<br>'.$module['phone2'];
                $this->session->data['already'] = $module['value'];
                $success = true;
            }
        }
    }
    if ($success == false){
        foreach ($modules as $module){
            if ($module['value'] == 'default'){
                echo $module['phone1'].'<br>'.$module['phone2'];
                $this->session->data['already'] = $module['value'];
            }
        }
    }
}