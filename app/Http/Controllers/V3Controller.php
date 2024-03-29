<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\V3 as Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\File;

class V3Controller extends Controller
{
    protected $errorMsg = '服务器出错';

    protected $category;
    protected $dimension;
    protected $key;

    // get image
    public function getImage(Request $request)
    {
        // set_time_limit(1000);

        $validator = validator($request->all(), [
            'dimension' => 'required|regex:/^\d+x\d+$/',
            'key' => 'integer',
            'category' => 'in:people,places,things',
        ], [
            'dimension.required' => 'dimension 缺失参数, 如: 300x200',
            'dimension.regex' => 'dimension 格式不正确, 正确格式为图片宽像素 + 小写字母 x + 高, 如: 300x200',
            'key.integer' => 'key 应为一个整数',
            'category.in' => '分类必须存在于 people,places,things',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $this->category = $request->input('category', 'places');
        $this->dimension = $request->input('dimension', '300x300');
        $this->key = $request->input('key', 1);


        // todo: 取图片耗时过长, 尝试 redirect 到图片服务器地址
        // https://placem.at/$category?w=$w&h=$h&random=$key
        $url = $this->generateRemoteImageUrl();
        header("Location: $url");
        return


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
            'key' => $this->key,
            'category' => $this->category,
        ])->first();

        $dir = public_path() . '/upload/v3';

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

            $randomStr = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));

            if (substr(sys_get_temp_dir(), -1, 1) == '/') {
                $tempFilePath = sys_get_temp_dir() . $randomStr . '.jpg';
            } else {
                $tempFilePath = sys_get_temp_dir() . '/' . $randomStr . '.jpg';
            }

            $url = $this->generateRemoteImageUrl();

            $hash = $this->curlImage($tempFilePath, $url);

            if (!$hash) {
                return false;
            }

            $image = Image::create([
                'category' => $this->category,
                'dimension' => $this->dimension,
                'hash' => $hash,
                'key' => $this->key,
            ]);

            $filePath = $dir . '/' . $image->hash . '.jpg';

            File::move($tempFilePath, $filePath);
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
    public function curlImage($tempFilePath, $url)
    {
        $client = new Client(['headers' => ['User-Agent' => $this->userAgents[array_rand($this->userAgents)]]]);

        try {
            $resource = fopen($tempFilePath, 'w');
            $client->get($url, [
                'sink' => $resource,
            ]);
        } catch (\Exception $e) {

            if (config('app.env') == 'local') {
                dd($e);
            }

            $this->errorMsg = '图片获取失败, https://placem.at 服务器不可用';
            return false;
        }

        $hash = md5_file($tempFilePath);

        $found = Image::where('hash', $hash)->exists();

        if ($found) {
            return $this->curlImage($tempFilePath, $url);
        }

        return $hash;
    }

    /**
     * generate remote url according to image dimension
     *
     * @param $dimension
     * @param $key
     * @return string
     *
     * example url: http://lorempixel.com/300/200/cats
     *
     */
    private function generateRemoteImageUrl()
    {
        list($w, $h) = explode('x', $this->dimension);
        $category = $this->category;
        $key = $this->key;

        return "https://placem.at/$category?w=$w&h=$h&random=$key";
    }
}
