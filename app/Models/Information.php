<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'informations';

    protected $keyType = 'string'; // 主キーの型を文字列（UUID）に設定
    public $incrementing = false; // 自動インクリメントを無効化

    protected $primaryKey = 'uuid';

    protected $casts = [
        'content' => 'json',
    ];

    protected $guarded = [
    ];
}
