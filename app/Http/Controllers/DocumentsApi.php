<?php

namespace App\Http\Controllers;

use App\Models\LookupModels\Document;
use Illuminate\Http\Request;
use Anchu\Ftp\Facades\Ftp;
use App\Mail\Generic;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class DocumentsApi extends Controller
{
    public function store(Request $request){
        $file = $request->file;
        $path = '/documents/'.$request->entity_type.'/'.$request->entity_id.'/';

        $document = new Document();
        $document->title = $request->title;
        $document->uploaded_by_id = $this->current_user()->id;
        $document->entity_type = $request->entity_type;
        $document->entity_id = $request->entity_id;
        $document->save();

        $filename = str_replace(' ', '', $document->title).$document->id;

        $document->filename = $filename;
        $document->type = $file->getClientOriginalExtension();
        $document->save();

        FTP::connection()->makeDir('/documents');
        FTP::connection()->makeDir('/documents/'.$request->entity_type);
        FTP::connection()->makeDir('/documents/'.$request->entity_type.'/'.$request->entity_id);
        FTP::connection()->uploadFile($file->getPathname(), $path.$filename.'.'.$file->getClientOriginalExtension());

        // Logging
        activity()
            ->performedOn($document->entity)
            ->causedBy($this->current_user())
            ->withProperties(['detail' => 'Added document "'.$document->title.'"', 'summary'=> false])
            ->log("Added documentation");

        // Construct and send email
        $paragraphs = [];
        $paragraphs[] = 'A new document titled "'.$document->title.'" has been uploaded to '.substr($document->entity_type, 0, -1).' '.($document->entity->ref ?? '').' by '.$this->current_user()->name;

        $to = null;
        $ccs = [];
        if($document->entity_type == 'invoices'){
            $to = $document->entity->raised_by;
            $ccs[] = $document->entity->project_manager;
        }
        else if($document->entity_type == 'claims' || $document->entity_type == 'mobile_payments' || $document->entity_type == 'lpos' || $document->entity_type == 'advances'){
            $to = $document->entity->requested_by;
            $ccs[] = $document->entity->project_manager;
        }
        else if($document->entity_type == 'requisitions'){
            $to = $document->entity->requested_by;
            $ccs[] = $document->entity->program_manager;
        }

        $subject = '[GIFMS] New document uploaded';
        $title = 'A new document has been uploaded.';
        
        Mail::queue(new Generic($to, $ccs, $subject, $title, $paragraphs, null, true));

        return response()->json(['msg'=>'Success', 'document'=>$document], 200);
    }


    public function getDocument($entity_type, $filename){
        try{
            $doc = Document::where('entity_type', $entity_type)->where('filename', $filename)->firstOrFail();
            $path = '/documents/'.$doc->entity_type.'/'.$doc->entity_id.'/'.$doc->filename.'.'.$doc->type;
            
            file_put_contents ( "E://Users//kennl//Documents//debug.txt" , PHP_EOL.$path , FILE_APPEND);$file_contents = FTP::connection()->readFile($path);
            $response = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
        catch (Exception $e){
            $response = Response::make($e->getMessage(), 500);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
    }


    public function update(Request $request){
        try{
            $doc = Document::findOrFail($request->id);

            $old_title = $doc->title;
            $new_title = $request->title;
            $log_messsage = '';
            if($old_title != $new_title){
                $doc->title = $request->title;
                $doc->save();

                $log_messsage = 'Document title changed from "'.$old_title.'" to "'.$new_title.'". ';
            }

            $file = $request->file;
            if(!empty($file) && $file != 0){
                $path = '/documents/'.$doc->entity_type.'/'.$doc->entity_id.'/';
                $filename = str_replace(' ', '', $doc->title).$doc->id;

                FTP::connection()->makeDir('/documents');
                FTP::connection()->makeDir('/documents/'.$doc->entity_type);
                FTP::connection()->makeDir('/documents/'.$doc->entity_type.'/'.$doc->entity_id);
                FTP::connection()->uploadFile($file->getPathname(), $path.$filename.'.'.$file->getClientOriginalExtension());

                $doc->type = $file->getClientOriginalExtension();
                $doc->save();

                $log_messsage = 'New file uploaded.';
            }

            // Logging
            activity()
                ->performedOn($doc->entity)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => $log_messsage])
                ->log("Documentation updated");

            return response()->json(['msg'=>'Success', 'document'=>$doc], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }


    public function delete($document_id){
        try {
            $doc = Document::findOrFail($document_id);
            FTP::connection()->delete('/documents/'.$doc->entity_type.'/'.$doc->entity_id.'/'.$doc->filename.$doc->type);
            $doc->delete();

            return response()->json(['msg'=>'success'], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }
}
