<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class FileHelper {
    public static function validateAvatar($file) 
{
    $errors = [];

    if (!$file) {
        $errors[] = 'No File Selected';
        return ['valid'=>False, 'errors'=>$errors];
    }

    $allowedMimes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if(!in_array($file->get_Mime_Type(), $allowedMimes)) {
        $errors = ["avatar must be jpeg, gif, png or webp"];

    }

    max_size = 2*1024*1024;
    if ($file->getSize()>max_size) {
        $errors[] = "max avatar size exceeded, must be less than 2MB";
    }

    $imageInfo = @getimagesize($file->getRealPath());
    if (!imageInfo) {
        $errors[] = "could not read image properties";
    }
    else {
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        if ($width<100 || $height<100) {
            $errors[] = "image must be at least 100x100 px";
        }

        if ($width>5000 || $height >5000) {
            $errors[] = "image must be no larger than 5000x5000 px";

        }

        return ["valid"=>empty($errors), 
        "errors"=>$errors];
    }

    public static function uploadAvatar($file, $userID) {
        try {
            $validation = self::validateAvatar($file);
            if(!$validation["valid"]){
                return ["success"=>false, "errors"=> $validation["errors"]];
            }

            $extension = $file->getClientOriginalExtension();
            $filename = "avatar_". $userID. "_". time()."."$extension;
            $path = $file->storeAs("avatars", $filename, "local");

            return ["success"=>true, "filename"=>$filename];
        } catch (\Exception $e) {
            return ["success"=?false, "errors"=> [$e->getMessage()]];
        }
        }

    public static function deleteAvatar($avatarPath) {
            try {
                $fullPath = storage_path('app/avatars/'.$avatarPath);

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    return true;
                }
                return false;
            } catch (\Exception $e) {
                return false;
            }
            }
        }

    public static function validateDocument($file) {
            $errors = [];
            if (!$file) {
                $errors[] = "no file selected";
                return ["valid"=>false, "errors"=>$errors];
            }

            $allowedMimes = [
                "application/pdf", 
                "application/vnd.opemxmlformats-officedocument.wordprocessingml.document", 
                "text/csv", 
                "application/csv", 
                "text/plain"
            ];
            $allowedExtensions = ["pdf", "docx", "csv"];

            $mime = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($mime, $allowedMimes)) {
                $errors[] = "file type not allowed. Use PDF, DOCX or CSV";

            }

            if (!in_array($extension, $allowedExtensions)) {
                $errors[] = 'File extensions must be .pdf, .docx or .csv';
            }
             max_size = 5*1024*1024;
            if ($file->getSize()>max_size) {
            $errors[] = "max file size exceeded, must be less than 10MB";

            if ($file->getSize()<1024) {
                $errors[] = "file must be at least 1KB";
            }

            return ["valid"=>empty($errors), "errors"=>$errors];
             

            
        }

        public static function uploadDocument($file, $userID)
        {
            try {
                $validation = self::validateDocument($file);
                if (!$validation["valid"]) {
                    return ["success"=>false, "errors"=> $validation["errors"]];
                }

                $originalName = $file->getClientOriginalName();
                $extnesion = $file->getClientOriginalExtension();
                $hashedName = "doc_".$userID."_".time()."_".hash("md5",$originalName).".".$extension;

                $path = $file->storeAs('documents/'.$userID, $hashedName, "local");

                return [
                    "success"=>true,
                    "filename"=>$hashedName,
                    "original_name"=>$originalName,
                    "type"=>strtolower($extension),
                    "size"=>$file->getSize()
                ];
            } catch (\Exception $e) {
                return ['success'=>false, 'errors'=>[$e->getMessage()]];
            }
        }

        public static function deleteDocument($documentPath, $userId) {
            try {
                $fullPath = storage_path('app/documents/'.$userId."/".$documentPath);

                if(file_exists($fullPath)) {
                    unlink($fullPath);
                    return true;
                }
                return false

            } catch (\Exception $e) {
                return false;
            }
        }

        public static function getDocumentMimeType($extension)
        {
            $mimes = [
                "pdf"=>"application/pdf", 
                "docx"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "csv"=>'text/csv'
            ];
            return $mimes[strtolower($extension)]?? 'application/octet-stream';
        }



    }








