<?php

namespace App\Console\Commands;

use App\Models\Requisitions\Requisition;
use Illuminate\Console\Command;
use Anchu\Ftp\Facades\Ftp;
use App\Models\Requisitions\RequisitionDocument;

class GenerateRequisitionDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:requisition-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates requisition document entries for existing requisitions before document titles were added.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $requisitions = Requisition::all();

        foreach($requisitions as $requisition){
            $sanitized_list = [];
            $directory = '/requisitions/'.$requisition->ref.'/';
            $listing = FTP::connection()->getDirListing($directory);
            foreach($listing as $item){
                $arr = explode('/',$item);
                $sanitized_list[] = explode('.',$arr[count($arr)-1])[0];
            }

            $counter = 0;
            foreach($sanitized_list as $doc){
                $requisition_doc = new RequisitionDocument();
                $requisition_doc->title = $doc;
                $requisition_doc->requisition_id = $requisition->id;
                $requisition_doc->uploaded_by_id = 2; // Admin
                $requisition_doc->filename = $requisition->ref.'doc_'.$counter;
                $requisition_doc->type = 'pdf';
                $requisition_doc->save();
                $counter += 1;
            }
        }
        
    }
}
