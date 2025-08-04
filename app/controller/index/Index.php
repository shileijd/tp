<?php

namespace app\controller\index;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return view('');
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
