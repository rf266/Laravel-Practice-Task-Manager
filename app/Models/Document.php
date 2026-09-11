<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'user_id', 'original_filename', 'stored_filename', 'file_type', 'file_size', 'file_path'

    ];


    protected $casts = [
        'file_size' =>'integer',

    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormattedAttribute(){
        $bytes = $this->file_size;
        $units = ['B', "KB", "MB", "GB"];
        $pow = floor(($bytes?  log($bytes :0)/log(1024)));
        $pow = min($pow, count($units)-1);
        $bytes /=(1<<(10*$pow));
        return round($bytes,2)." ". $units[$pow];
    }

    public function getDownloadUrlAttribute(){
        return route('documents.download',$this->id);
    }

}
