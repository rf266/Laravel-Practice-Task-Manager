<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentUploadController extends Controller
{
    public function index() {
        $user = auth()->user();
        $documents = $user->documents()->latest()->paginate(10);

        return view('documents.index', [
            "documents" => $documents,
            "documentCount"=>$user->documentCount(), 
            "canUpload"=>$user->canUploadDocument()
        ]);
    }

    public function create() {
        $user = auth()->user();

        if (!$user->canUploadDocuemnt()) {
            return redirect()->route('documents.index')->with('error', 'you have reached the maximum of 5 documents');
        }

        return view('documents.upload',['documentCount'=>$user->documentCount()];)
    }

    public functon store(Request $request) {
        $user = auth()->user();

        if (!$user->canUploadDocument()) {
            return response()->json([
                'success'=>false, 
                'error'=>"you have reached the maximum of 5 documents";
            ], 422);
        }

        if(!$request->hasFile('document')) {
            return response()->json([
                'success'=>false, 
                'error'=>'no file provided'
            ],400);
        }

        $file = $request->file('document');

        $result = FileHelper::uploadDocument($file, $user->id);

        if(!$result['success']) {
            return response()->json([
                'success'=>false, 
                "errors"=>$result['errors']
            ], 422);
        }

        $document = Document::create([
            'success'=>true, 
            'message'=>'document uploaded successfully',
            'document'=>[
                'id'=>$document->id,
                'filename'=>$document->original_filename,
                'size'=>$document->file_size_formatted,
                'type'=>$document->file_type,
                'download_url'=>$document->download_url
            ]
        ]);
    }

    public function download(Document $document) {

        if(auth()->user()->id !==$document->user_id) {
            abort(403, 'unauthorised');
        }

        $filePath = storage_path('app/'.$document->file_path);

        if (!file_exists($filePath)){
            abort(404, 'file not found ');
        }

        $mimetype = FileHelper::getDocumentMimeType($document->file_type);

        return response()->download(
            $filePath, 
            $document->original_filename, 
            ["Content-Type" =>$mimeType]
        );
    }

    public function destroy(Document $document) {
        
        if (auth()->user()->id !== $document->user_id) {
            return response()->json([
                'success'=>false, 'error'=>"Unauthorised"
            ],403);
        }

        FileHelper::deleteDocument($document->stored_filename, auth()->user()-id);
        $document->delete();
        return response()->json([
            'success'=>true, 'message'=>'document deleted'
        ]);
    }
}
