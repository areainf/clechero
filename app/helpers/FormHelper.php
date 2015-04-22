<?php
class FormHelper{
    public static function options_for($array, $selected=0, $haskey=true){
        if(empty($array)) return "";
        $options = "";
        foreach ($array as $key => $value) {
            $val = "";
            if ($haskey)
                $val = ' value="'.$key.'" ';
            $sel = "";
            if($key == $selected)
                $sel = ' selected="selected" ';
            $options .= '<option ' . $val . $sel . '>' . $value . '</option>';
        }
        return $options;
    }
    public static function options_for_collection($collection,  $key, $value, $selected=0){
        if(empty($collection)) return "";
        $options = "";
        foreach ($collection as $data) {
            $k = $data->$key;
            $val = ' value="'.$k.'"';
            $sel = "";
            if($k == $selected)
                $sel = ' selected="selected" ';
            if(isset($data->$value ))
                $texto = $data->$value;
            else
                $texto = $data->$value();

            $options .= '<option ' . $val . $sel . '>' . $texto . '</option>';
        }
        return $options;
    }
    public static function checkbox_tag($name, $value, $options = array()){
        $ck = '<input type="checkbox" name="'.$name.'" value="'.$value.'" ';
        if(!empty($options)){
            foreach ($options as $key => $value) {
                if($key == 'checked'){
                    if($value)
                       $ck .=' '.$key.'="checked"';
                }
                else
                    $ck .=' '.$key.'="'.$value.'"';
            }
        }
        $ck .= '>';
        return $ck;
    }
    public static function hidden_tag($name, $value, $options = array()){
        $hid = '<input type="hidden" name="'.$name.'" value="'.$value.'" ';
        if(!empty($options)){
            foreach ($options as $key => $value) {
                $hid .=' '.$key.'="'.$value.'"';
            }
        }
        $hid .= '>';
        return $hid;
    }

    public static function link_to($url, $text, $options = array()){
        $a = '<a href="'.$url.'" ';
        if(!empty($options)){
            foreach ($options as $key => $value) {
                if($key=='confirm')
                    $a .='onClick="return confirm(\''.$value.'\')"';
                elseif($key == 'title'){
                    $a .= ' data-toggle="tooltip" data-placement="top" title="'.$value.'"';
                }
                else
                    $a .=' '.$key.'="'.$value.'"';
            }
        }
        $a .= '>'.$text.'</a>';
        return $a;
    }
}
?>