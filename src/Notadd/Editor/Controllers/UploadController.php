<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Editor\Controllers;
use Illuminate\Http\Request;
use Notadd\Foundation\Routing\Controller;
/**
 * Class UploadController
 * @package Notadd\Editor\Controllers
 */
class UploadController extends Controller {
    public function index() {

    }
    public function store(Request $request) {
        dd($request);
    }
}