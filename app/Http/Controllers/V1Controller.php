<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\V1 as Image;
use Illuminate\Http\Request;

use App\Http\Requests;

class V1Controller extends Controller
{
    protected $errorMsg = '服务器出错';

    protected $dimension;
    protected $key;

    // get image
    public function getImage(Request $request)
    {
        set_time_limit(1000);

        $validator = validator($request->all(), [
            'dimension' => 'required|regex:/^\d+x\d+$/',
            'key'       => 'integer',
        ], [
            'dimension.required' => 'dimension 缺失参数, 如: 300x200',
            'dimension.regex' => 'dimension 格式不正确, 正确格式为图片宽像素 + 小写字母 x + 高, 如: 300x200',
            'key.integer'        => 'key 应为一个整数',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $this->dimension = $request->dimension;
        $this->key = $request->key ? $request->key : 1;

        $file = $this->retrieveFile();

        if (!$file) {
            return response($this->errorMsg, 500);
        }
        return response()->file($file);
    }

    private function retrieveFile()
    {
        $image = Image::where([
            'dimension' => $this->dimension,
            'key'       => $this->key,
        ])->first();

        $dir = public_path() . '/upload/v1';

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        // Validate directory path
        if (!is_dir($dir) || !is_writable($dir)) {
            $this->errorMsg = '服务器出错, 请检查图片目录是否可写';
            return false;
        }

        $filePath = '';
        if (!!$image && $image->hash) {
            $filePath = $dir . '/' . $image->hash . '.jpg';
        }

        if (!$image || (!!$image && !is_file($filePath))) {

            // delete db record
            !!$image && $image->delete();

            $hash = $this->getUniqueFilename();

            $filePath = $dir . '/' . $hash . '.jpg';

            $url = $this->generateRemoteImageUrl();

            $hash = $this->curlImage($filePath, $url, $hash);

            if (!$hash) {
                return false;
            }

            Image::create([
                'dimension' => $this->dimension,
                'hash'      => $hash,
                'key'       => $this->key,
            ]);
        }

        return $filePath;
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public function curlImage($filePath, $url, $hash)
    {
        $client = new Client();

        $res = null;

        try {
            $resource = fopen($filePath, 'w');
            $res = $client->get($url, [
                'sink' => $resource,
            ]);
        } catch (\Exception $e) {

            if (config('app.env') == 'local') {
                dd($e);
            }

            $this->errorMsg = '图片获取失败, placehold.it 服务器不可用';
            return false;
        }

        $found = Image::where('hash', $hash)->exists();

        if ($found) {
            return $this->curlImage($filePath, $url, $hash);
        }

        return $hash;
    }

    /**
     * get unique filename
     *
     * @param $key
     * @return string
     */
    private function getUniqueFilename()
    {
        $hash = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $found = Image::where('hash', $hash)->first();

        if (!$found) {
            return $hash;
        }

        return $this->getUniqueFilename();
    }

    /**
     * generate remote url according to image dimension
     *
     * @param $dimension
     * @param $key
     * @return string
     *
     * example url: https://placeholdit.imgix.net/~text?txtsize=72&txt=350x150&w=350&h=150
     *
     */
    private function generateRemoteImageUrl()
    {
        $dimension = $this->dimension;
        list($w, $h) = explode('x', $dimension);
        $min = $w > $h ? $h : $w;
        $textSize = ($min / 5) > 10 ? $min / 5 : 10;

        $key = $this->key;

        return "https://placeholdit.imgix.net/~text?txtsize=$textSize&txt=$key:$dimension&w=$w&h=$h";
    }
}
