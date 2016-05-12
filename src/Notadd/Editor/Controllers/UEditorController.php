<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 10:25
 */
namespace Notadd\Editor\Controllers;
use Illuminate\Http\Request;
use Notadd\Editor\Lists;
use Notadd\Editor\Uploaders\UploadCatch;
use Notadd\Editor\Uploaders\UploadFile;
use Notadd\Editor\Uploaders\UploadScrawl;
use Notadd\Foundation\Routing\Controller;
/**
 * Class UEditorController
 * @package Notadd\Editor\Controllers
 */
class UEditorController extends Controller {
    /**
     * @var array
     */
    protected $config = [
        "imageActionName" => "uploadimage",
        "imageFieldName" => "upfile",
        "imageMaxSize" => 2048000,
        "imageAllowFiles" => [
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".bmp"
        ],
        "imageCompressEnable" => true,
        "imageCompressBorder" => 1600,
        "imageInsertAlign" => "none",
        "imageUrlPrefix" => "",
        "imagePathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        "scrawlActionName" => "uploadscrawl",
        "scrawlFieldName" => "upfile",
        "scrawlPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        "scrawlMaxSize" => 2048000,
        "scrawlUrlPrefix" => "",
        "scrawlInsertAlign" => "none",
        "snapscreenActionName" => "uploadimage",
        "snapscreenPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        "snapscreenUrlPrefix" => "",
        "snapscreenInsertAlign" => "none",
        "catcherLocalDomain" => [
            "127.0.0.1",
            "localhost",
            "img.baidu.com"
        ],
        "catcherActionName" => "catchimage",
        "catcherFieldName" => "source",
        "catcherPathFormat" => "/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        "catcherUrlPrefix" => "",
        "catcherMaxSize" => 2048000,
        "catcherAllowFiles" => [
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".bmp"
        ],
        "videoActionName" => "uploadvideo",
        "videoFieldName" => "upfile",
        "videoPathFormat" => "/uploads/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}",
        "videoUrlPrefix" => "",
        "videoMaxSize" => 102400000,
        "videoAllowFiles" => [
            ".flv",
            ".swf",
            ".mkv",
            ".avi",
            ".rm",
            ".rmvb",
            ".mpeg",
            ".mpg",
            ".ogg",
            ".ogv",
            ".mov",
            ".wmv",
            ".mp4",
            ".webm",
            ".mp3",
            ".wav",
            ".mid"
        ],
        "fileActionName" => "uploadfile",
        "fileFieldName" => "upfile",
        "filePathFormat" => "/uploads/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}",
        "fileUrlPrefix" => "",
        "fileMaxSize" => 51200000,
        "fileAllowFiles" => [
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".bmp",
            ".flv",
            ".swf",
            ".mkv",
            ".avi",
            ".rm",
            ".rmvb",
            ".mpeg",
            ".mpg",
            ".ogg",
            ".ogv",
            ".mov",
            ".wmv",
            ".mp4",
            ".webm",
            ".mp3",
            ".wav",
            ".mid",
            ".rar",
            ".zip",
            ".tar",
            ".gz",
            ".7z",
            ".bz2",
            ".cab",
            ".iso",
            ".doc",
            ".docx",
            ".xls",
            ".xlsx",
            ".ppt",
            ".pptx",
            ".pdf",
            ".txt",
            ".md",
            ".xml"
        ],
        "imageManagerActionName" => "listimage",
        "imageManagerListPath" => "/uploads/ueditor/php/upload/image/",
        "imageManagerListSize" => 20,
        "imageManagerUrlPrefix" => "",
        "imageManagerInsertAlign" => "none",
        "imageManagerAllowFiles" => [
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".bmp"
        ],
        "fileManagerActionName" => "listfile",
        "fileManagerListPath" => "/uploads/ueditor/php/upload/file/",
        "fileManagerUrlPrefix" => "",
        "fileManagerListSize" => 20,
        "fileManagerAllowFiles" => [
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".bmp",
            ".flv",
            ".swf",
            ".mkv",
            ".avi",
            ".rm",
            ".rmvb",
            ".mpeg",
            ".mpg",
            ".ogg",
            ".ogv",
            ".mov",
            ".wmv",
            ".mp4",
            ".webm",
            ".mp3",
            ".wav",
            ".mid",
            ".rar",
            ".zip",
            ".tar",
            ".gz",
            ".7z",
            ".bz2",
            ".cab",
            ".iso",
            ".doc",
            ".docx",
            ".xls",
            ".xlsx",
            ".ppt",
            ".pptx",
            ".pdf",
            ".txt",
            ".md",
            ".xml"
        ]
    ];
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        $action = $request->get('action');
        switch($action) {
            case 'config':
                $result = $this->config;
                break;
            case 'uploadimage':
                $config = [
                    "pathFormat" => $this->config['imagePathFormat'],
                    "maxSize" => $this->config['imageMaxSize'],
                    "allowFiles" => $this->config['imageAllowFiles'],
                    'fieldName' => $this->config['imageFieldName'],
                ];
                $result = with(new UploadFile($config, $request))->upload();
                break;
            case 'uploadscrawl':
                $config = [
                    "pathFormat" => $this->config['scrawlPathFormat'],
                    "maxSize" => $this->config['scrawlMaxSize'],
                    "oriName" => "scrawl.png",
                    'fieldName' => $this->config['scrawlFieldName'],
                ];
                $result = with(new UploadScrawl($config, $request))->upload();
                break;
            case 'uploadvideo':
                $config = [
                    "pathFormat" => $this->config['videoPathFormat'],
                    "maxSize" => $this->config['videoMaxSize'],
                    "allowFiles" => $this->config['videoAllowFiles'],
                    'fieldName' => $this->config['videoFieldName'],
                ];
                $result = with(new UploadFile($config, $request))->upload();
                break;
            case 'uploadfile':
                $config = [
                    "pathFormat" => $this->config['filePathFormat'],
                    "maxSize" => $this->config['fileMaxSize'],
                    "allowFiles" => $this->config['fileAllowFiles'],
                    'fieldName' => $this->config['fileFieldName'],
                ];
                $result = with(new UploadFile($config, $request))->upload();
                break;
            case 'listimage':
                $result = with(new Lists(
                    $this->config['imageManagerAllowFiles'],
                    $this->config['imageManagerListSize'],
                    $this->config['imageManagerListPath'],
                    $request))->getList();
                break;
            case 'listfile':
                $result = with(new Lists(
                    $this->config['fileManagerAllowFiles'],
                    $this->config['fileManagerListSize'],
                    $this->config['fileManagerListPath'],
                    $request))->getList();
                break;
            case 'catchimage':
                $config = [
                    "pathFormat" => $this->config['catcherPathFormat'],
                    "maxSize" => $this->config['catcherMaxSize'],
                    "allowFiles" => $this->config['catcherAllowFiles'],
                    "oriName" => "remote.png",
                    'fieldName' => $this->config['catcherFieldName'],
                ];
                $sources = $request->input($config['fieldName']);
                $list = [];
                foreach ($sources as $imgUrl) {
                    $config['imgUrl'] = $imgUrl;
                    $info = with(new UploadCatch($config, $request))->upload();
                    array_push($list, [
                        "state" => $info["state"],
                        "url" => $info["url"],
                        "size" => $info["size"],
                        "title" => htmlspecialchars($info["title"]),
                        "original" => htmlspecialchars($info["original"]),
                        "source" => htmlspecialchars($imgUrl)
                    ]);
                }
                $result = [
                    'state' => count($list) ? 'SUCCESS' : 'ERROR',
                    'list' => $list
                ];
                break;
        }
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}