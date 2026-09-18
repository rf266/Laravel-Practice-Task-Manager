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
    $bytes = (int) $this->file_size;

    $units = ['B', 'KB', 'MB', 'GB'];
    $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
    $power = min($power, count($units) - 1);

    $value = $bytes / (1024 ** $power);

    return round($value, 2) . ' ' . $units[$power];
}

    public function getDownloadUrlAttribute(){
        return route('documents.download',$this->id);
    }

}
