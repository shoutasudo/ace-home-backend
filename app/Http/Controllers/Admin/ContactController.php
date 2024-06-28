<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * getAll
     */
    public function list(): JsonResponse
    {
        $res = Contact::orderByRaw('status = 0 DESC')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($res, Response::HTTP_OK);
    }

    /**
     * getOne
     * -> automatically status change alreadyRead
     */
    public function detail(Request $request): JsonResponse
    {
        $res = Contact::find($request->id);
        if ($res) {
            $res->status = 1;
            $res->save();
            return response()->json($res, Response::HTTP_OK);
        } else {
            return response()->json(['message' => '内容が取得できませんでした'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * delete
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        try {
            DB::beginTransaction();
            Contact::findOrFail($id)->delete();
            DB::commit();
            $res = Contact::orderBy('created_at', 'desc')->get();
            return response()->json($res, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['message' => '削除に失敗しました'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * status change unread
     */
    public function unRead(Request $request): JsonResponse
    {
        $id = $request->id;
        try {
            $res = Contact::find($id);
            if ($res) {
                $res->status = 0;
                $res->save();
                return response()->json($res, Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => '未読処理に失敗しました'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
