<?php
namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StaffCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function($staff) {
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'comment' => $staff->comment,
                'created_at' => $staff->created_at,
            ];
        });
    }
}
