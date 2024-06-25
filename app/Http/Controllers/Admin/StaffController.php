<?php

namespace App\Http\Controllers\Admin;

use App\Models\Staff;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Resources\Staff\StaffCollection;
use App\Http\Resources\Staff\StaffResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): StaffCollection
    {
        $staff = Staff::all();
        return new StaffCollection($staff);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request): JsonResponse
    {
        $attributes = $request->only(['name', 'comment', 'role']);

        try {
            DB::beginTransaction();

            $staff = Staff::create([
                'name' => $attributes['name'],
                'comment' => $attributes['comment'],
                'role' => $attributes['role']
            ]);

            $path = null;
            // 画像の保存
            if ($request->hasFile('image')) {
                $destination = 'staff/' . $staff->id;
                $path = $request->file('image')->store($destination, 'public');
                $staff->image_path = $path;
                $staff->save();
            }
            DB::commit();
            return response()->json(['message' => 'スタッフが正常に作成されました'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(print_r($e, true));
            return response()->json(['message' => 'スタッフ作成に失敗しました'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff): StaffResource
    {
        return new StaffResource($staff);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff): JsonResponse
    {
        $attributes = $request->only(['name', 'comment', 'role']);

        try {
            DB::beginTransaction();

            $staff->update([
                'name' => $attributes['name'],
                'comment' => $attributes['comment'],
                'role' => $attributes['role']
            ]);

            $path = null;
            // 画像の保存
            if ($request->hasFile('image')) {
                $destination = 'staff/' . $staff->id;

                // 指定されたディレクトリ内のファイルを全て削除
                Storage::disk('public')->deleteDirectory($destination);

                // 新しい画像を保存
                $path = $request->file('image')->store($destination, 'public');
                $staff->image_path = $path;
                $staff->save();
            }

            DB::commit();
            return response()->json(['message' => 'スタッフが正常に更新されました'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(print_r($e, true));
            return response()->json(['message' => 'スタッフ更新に失敗しました'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->staffId;
        try {
            DB::beginTransaction();
            $staff = Staff::findOrFail($id);
            $staff->delete();

            $destination = 'staff/' . $id;
            // 指定されたディレクトリ内のファイルを全て削除
            Storage::disk('public')->deleteDirectory($destination);
            DB::commit();
            return response()->json(['message' => 'スタッフが正常に削除されました'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(print_r($e, true));
            return response()->json(['message' => 'スタッフの削除に失敗しました'], 500);
        }
        Log::debug(print_r($request->staffId, true));
    }
}
