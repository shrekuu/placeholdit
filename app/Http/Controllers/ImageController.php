<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{
    protected $errorMsg = '服务器出错';

    public function getImage(Request $request, $dimension, $key)
    {
        $file = $this->retrieveFile($dimension, $key);
        if (!$file) {
            return response($this->errorMsg, 500);
        }
        return response()->file($file);
    }

    private function retrieveFile($dimension, $key)
    {
        $image = Image::where([
            'dimension' => $dimension,
            'key'       => $key
        ])->first();

        $fullPath = public_path() . '/upload';

        $absPath = '';
        if (!!$image && $image->hash) {
            $absPath = $fullPath . '/' . $image->hash . '.jpg';
        }

        if (!$absPath || !is_file($absPath)) {

            // delete db record
            !!$image && $image->delete();

            $hash = $this->curlImage($fullPath, $dimension, $key);

            if (!$hash) {
                return false;
            }

            $image = Image::create([
                'dimension' => $dimension,
                'hash'      => $hash,
                'key'       => $key,
            ]);
        }

        return $fullPath . '/' . $image->hash . '.jpg';
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public function curlImage($dir = null, $dimension, $key)
    {
        $dir = is_null($dir) ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible
        // Validate directory path
        if (!is_dir($dir) || !is_writable($dir)) {
            $this->errorMsg = '服务器出错, 请检查图片目录是否可写';
            return false;
            // throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $hash = $this->getUniqueFilename($key);
        $filename = $hash . '.jpg';

        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $url = $this->generateRemoteImageUrl($dimension, $key);

        // save file
        if (function_exists('curl_exec')) {
            // use cURL
            $fp = fopen($filepath, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            $success = curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        } elseif (ini_get('allow_url_fopen')) {
            // use remote fopen() via copy()
            $success = copy($url, $filepath);
        } else {
            $this->errorMsg = '服务器出错, 请查看是否开启了 PHP_CURL 模块';
            return false;
            // return new \RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        if (!$success) {
            // could not contact the distant URL or HTTP error - fail silently.
            $this->errorMsg = '服务器出错, 请查看 http://placehold.it 是否可以正常访问';
            return false;
        }

        return $hash;
    }

    /**
     * get unique filename
     *
     * @param $key
     * @return string
     */
    private function getUniqueFilename($key)
    {
        $hash = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $found = Image::where('hash', $hash)->first();

        if (!$found) {
            return $hash;
        }

        return $this->getUniqueFilename($key);
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
    private function generateRemoteImageUrl($dimension, $key)
    {
        list($w, $h) = explode('x', $dimension);
        $min = $w > $h ? $h : $w;
        $textSize = ($min / 5) > 10 ? $min / 5 : 10;

        return "https://placeholdit.imgix.net/~text?txtsize=$textSize&txt=$key:$dimension&w=$w&h=$h";
    }
}
