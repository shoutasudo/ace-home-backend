<?php

namespace App\Services;

use App\Models\Information;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminInfoService
{


  /**
   * create
   *
   * @param string $title
   * @param string $tag
   * @return Information
   */
  public function create(String $title, String $tag, String $uuid): Information
  {
    $info = Information::create([
      'uuid' => $uuid,
      'title' => $title,
      'tag' => $tag
    ]);

    return $info;
  }

  public function topImgStore($file, $info)
  {
    if ($file) {
      $topImgDirectory = 'info/top/' . $info->uuid; // 保存先ディレクトリ名
      $imgFileName = uniqid('img_') . '.' . $file->getClientOriginalExtension(); // ファイル名を生成
      Storage::disk('public')->putFileAs($topImgDirectory, $file, $imgFileName);

      // 保存したファイルのパスを取得
      $img_path = Storage::disk('public')->url($topImgDirectory . '/' . $imgFileName);
    } else {
      $img_path = null; // もし画像がアップロードされていない場合の処理（必要に応じて）
    }
    return $img_path;
  }


  function recursiveIterateObject(&$object, $callback, $currentKey = null)
  {
    // オブジェクトの各プロパティに対して処理を行う
    foreach ($object as $key => &$value) {
      // コールバック関数を呼び出す
      $callback($value, $key);

      // もしプロパティがオブジェクトまたは配列であれば再帰的に処理を行う
      if (is_object($value) || is_array($value)) {
        $this->recursiveIterateObject($value, $callback, $key);
      }
    }
  }


  public function contentImgStore(String $src, String $uuid)
  {
    $base64Data = substr($src, strpos($src, ',') + 1);
    $binaryData = base64_decode($base64Data);

    if (empty($binaryData)) {
      throw new Exception('Base64データのデコードに失敗しました');
    }

    // MIMEタイプから拡張子を取得する
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_buffer($finfo, $binaryData);
    finfo_close($finfo);

    $extension = '';
    switch ($mimeType) {
      case 'image/png':
        $extension = 'png';
        break;
      case 'image/jpeg':
        $extension = 'jpg';
        break;
      case 'image/gif':
        $extension = 'gif';
        break;
        // 他のファイルタイプに対応する場合は適宜追加する
      default:
        $extension = 'dat'; // デフォルト拡張子など
        break;
    }

    $contentDirectory = 'info/content/' . $uuid; // 保存先ディレクトリ名

    // 一意のファイル名を生成（ユニークな名前を使用）
    $contentFileName = uniqid('content_') . '.' . $extension;
    // ファイルストレージに保存
    $contentFilePath = $contentDirectory . '/' . $contentFileName;
    Storage::disk('public')->put($contentFilePath, $binaryData);

    // 保存したファイルのパスを更新
    return  Storage::disk('public')->url($contentFilePath);
  }

  public function informationStoreUpdate($info, $contentDecode, $img_path)
  {
    return $info->update([
      'content' => $contentDecode,
      'img_path' => $img_path
    ]);
  }

  public function topImgFileDelete($topImgDirectory)
  {
    $topImgFiles = Storage::disk('public')->files($topImgDirectory);

    Storage::disk('public')->delete($topImgFiles);

    Storage::disk('public')->deleteDirectory($topImgDirectory);
  }

  public function contentImgFileDelete($contentDirectory)
  {
    $contentFiles = Storage::disk('public')->files($contentDirectory);

    Storage::disk('public')->delete($contentFiles);

    Storage::disk('public')->deleteDirectory($contentDirectory);
  }

  public function informationEditUpdate($registeredInfo, $title, $tag, $img_path, $contentDecode)
  {
    return $registeredInfo->update([
      'title' => $title,
      'tag' => $tag,
      'img_path' => isset($img_path) ?  $img_path : $registeredInfo->img_path,
      'content' => $contentDecode
    ]);
  }
}
