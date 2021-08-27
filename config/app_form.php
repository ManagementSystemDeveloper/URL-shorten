<?php
// https://api.cakephp.org/3.4/class-Cake.View.Helper.FormHelper.html#$_defaultConfig
return [
    'inputContainer' => '<div class="form-group {{type}} {{required}}">
        {{content}}<span class="help-block">{{help}}</span></div>',
    'error' => '<span class="help-block">{{content}}</span>',
    'inputContainerError' => '<div class="form-group has-error {{type}} {{required}}">{{content}}{{error}}</div>'
];
