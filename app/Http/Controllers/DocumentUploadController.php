<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\DocumentHelper;
use App\Models\Document;
use App\Models\User;

class DocumentUploadController extends Controller
{
    public function index() {
        $user = User::findOrFail(session('user_id'));
        $documents = $user->documents()->latest()->paginate(10);

        return view('documents.index', [
            "documents" => $documents,
            "documentCount"=>$user->documentCount(), 
            "canUpload"=>$user->canUploadDocument()
        ]);
    }

    public function create() {
        $user = User::findOrFail(session('user_id'));

        if (!$user->canUploadDocument()) {
            return redirect()->route('documents.index')->with('error', 'you have reached the maximum of 5 documents');
        }

        return view('documents.upload',['documentCount'=>$user->documentCount()]);
    }

    public function store(Request $request) {
        $user = User::findOrFail(session('user_id'));

        if (!$user->canUploadDocument()) {
            return response()->json([
                'success'=>false, 
                'error'=>"you have reached the maximum of 5 documents"
            ], 422);
        }

        if(!$request->hasFile('document')) {
            return response()->json([
                'success'=>false, 
                'error'=>'no file provided'
            ],400);
        }

        $file = $request->file('document');

        $result = DocumentHelper::uploadDocument($file, $user->id);

        if(!$result['success']) {
            return response()->json([
                'success'=> false, 
                "errors" => $result['errors']
            ], 422);
        }

        $document = Document::create([
            'user_id' => $user->id, 
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $result['filename'], 
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'file_size' => $file->getSize(),
            'file_path' => 'documents/' . $user->id . '/' . $result['filename'],
        ]);
        

        return response()->json([
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

        if(session('user_id') !== $document->user_id) {
            abort(403, 'unauthorised');
        }

        $filePath = storage_path('app/public/'.$document->file_path);

        if (!file_exists($filePath)){
            abort(404, 'file not found ');
        }

        $mimetype = DocumentHelper::getDocumentMimeType($document->file_type);

        return response()->download(
            $filePath, 
            $document->original_filename, 
            ["Content-Type" =>$mimetype]
        );
    }

    public function destroy(Document $document) {
        
        if (session('user_id') !== $document->user_id) {
            return response()->json([
                'success'=>false, 'error'=>"Unauthorised"
            ],403);
        }

        DocumentHelper::deleteDocument($document->stored_filename, session('user_id'));
        $document->delete();
        return response()->json([
            'success'=>true, 'message'=>'document deleted'
        ]);
    }
}
