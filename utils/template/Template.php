<?php

namespace app\utils\template;

class Template
{
    static function template($icon, $style=''){
        return [
            'template'=>'<div class="form-group" style="'.$style.'" >
                            <label>{label}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="'.$icon.'"></i>
                                    </div>
                                </div>
                                {input}
                            </div>
                            <span style="color:red">{error}</span>
                        </div>'];
    }

    static function summernote(){
        return $summernote=[
            'template'=>'<div class="form-group">
                            <label>{label}</label>
                            <div class="input-group">
                                {input}
                            </div>
                            <span style="color:red">{error}</span>
                        </div>'];
    }
    function radio(){
        return $radio=[
            'template'=>'<div class="form-group">
                            <label>{label}</label>
                            <div class="input-group">
                                {input}
                            </div>
                            <span style="color:red">{error}</span>
                        </div>'];
    }
    function image(){
        return $image=[
            'template'=>'<div class="form-group">
                <label>{label}</label>
                {input}
                <span style="color:red">{error}</span>
            </div>'];
    }
}