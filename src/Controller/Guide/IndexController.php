<?php

namespace Com\Youzan\ZanPhpIo\Controller\Guide;


use Com\Youzan\ZanPhpIo\Controller\Base\BaseController as Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->assign('title', '入门');
        yield $this->display('guide/index');
    }

}
