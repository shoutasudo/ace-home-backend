<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Infomation\StoreRequest;
use App\Http\Requests\Admin\Infomation\UpdateRequest;
use App\Services\AdminInfoService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Information;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class InformationController extends Controller
{
    private $informationService;

    public function __construct(AdminInfoService $informationService)
    {
        $this->informationService = $informationService;
    }

    /**
     * インフォ・全件取得
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $info = Information::select('*')->orderBy('created_at', 'desc')->get();
        return response()->json($info, Response::HTTP_OK);
    }


    /**
     * UUID生成
     *
     * @return JsonResponse
     */
    public function createUuid() :JsonResponse
    {
        $uuid = Str::uuid()->toString();
        return response()->json($uuid, Response::HTTP_OK);
    }



    /**
     * リッチテキストの画像をストレージに保存しパスを返す
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function contentImgStore(Request $request) : JsonResponse
    {

        $src = $request->input('src');
        $uuid = $request->input('uuid');
        try {
            // base64ならtrue・pathならfalse
            if ((strpos($src, '/') === false && strpos($src, '\\') === false) || (base64_decode(substr($src, strpos($src, ',') + 1), true) !== false)) {
                $path = $this->informationService->contentImgStore($src,$uuid);
                return response()->json($path, Response::HTTP_OK);
            } else {
                // pathならそのまま返す
                return response()->json($src, Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * 配列に入っているパス以外のファイルを削除
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteFilesExcept(Request $request) :JsonResponse
    {
        // リクエストから除外するファイルパスを取得
        $excludePaths = $request->input('exclude_paths', []);

        $uuid = $request->input('uuid');
        $relativePaths = [];
        // URLを相対パスに変換
        foreach ($excludePaths as $key => $v) {
            $path = parse_url($v, PHP_URL_PATH);
            $relativePaths[$key] = ltrim($path, '/storage/'); // 'storage/' 部分を除去
        }

        // ファイルのあるディレクトリを指定
        $directory = 'info/content/' . $uuid;

        // 指定ディレクトリ内のすべてのファイルを取得
        $files = Storage::disk('public')->files($directory);

        // 削除するファイルリストを初期化
        $filesToDelete = array_diff($files, $relativePaths);

        // ファイルを削除
        foreach ($filesToDelete as $file) {
            Storage::disk('public')->delete($file);
        }

        return response()->json([
            'message' => '指定されたパス以外のファイルを削除しました',
            'deleted_files' => $filesToDelete
        ]);
    }

    /**
     * インフォ・画像/レコード登録
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        // 入力データの取得
        $uuid = $request->input('uuid');
        $title = $request->input('title');
        $tag = $request->input('tag');
        $file = $request->file('file'); // 仮の値として設定

        $content = $request->input("content");
        $contentDecode = json_decode($content);


        // データベースのトランザクションを開始
        try {
            DB::beginTransaction();

            $info = $this->informationService->create($title, $tag,$uuid);

            $img_path = $this->informationService->topImgStore($file, $info);

            // $result がオブジェクトであることを確認する
            $registeredInfo = $this->informationService->informationStoreUpdate($info, $contentDecode, $img_path);

            // トランザクションのコミット
            DB::commit();

            return response()->json($registeredInfo, Response::HTTP_OK);
        } catch (Exception $e) {
            // トランザクションのロールバック
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * インフォ・更新画面に必要なレコード取得
     *
     * @param Information $registeredInfo
     * @return JsonResponse
     */
    public function edit(Information $registeredInfo): JsonResponse
    {
        return response()->json($registeredInfo, Response::HTTP_OK);
    }


    /**
     * インフォ・画像/レコード更新
     *
     * @param UpdateRequset $request
     * @param Information $registeredInfo
     * @return JsonResponse

     */
    public function update(UpdateRequest $request, Information $registeredInfo): JsonResponse
    {
        $title = $request->input('title');
        $tag = $request->input('tag');
        $file = $request->file('file'); // 仮の値として設定


        $content = $request->input("content");
        $contentDecode = json_decode($content);
        try {
            DB::beginTransaction();

            $topImgDirectory = 'info/top/' . $registeredInfo->uuid;


            $img_path = null;
            if (isset($file)) {

                $this->informationService->topImgFileDelete($topImgDirectory);
                $img_path = $this->informationService->topImgStore($file, $registeredInfo);
            }


            $registeredInfo = $this->informationService->informationEditUpdate($registeredInfo, $title, $tag, $img_path, $contentDecode);

            DB::commit();


            return response()->json($registeredInfo, Response::HTTP_OK);
        } catch (Exception $e) {
            // トランザクションのロールバック
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * インフォ・画像/レコード削除
     *
     * @param Information $registeredInfo
     * @return JsonResponse
     */
    public function delete(Information $registeredInfo): JsonResponse
    {
        $topImgDirectory = 'info/top/' . $registeredInfo->uuid;
        $contentDirectory = 'info/content/' . $registeredInfo->uuid;

        try {
            DB::beginTransaction();

            $this->informationService->topImgFileDelete($topImgDirectory);

            $this->informationService->contentImgFileDelete($contentDirectory);

            $registeredInfo->delete();

            $result = Information::select('*')->get();

            DB::commit();

            return response()->json($result, Response::HTTP_OK);
        } catch (Exception $e) {
            // トランザクションのロールバック
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
