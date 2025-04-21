<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
   
    static function loadDocuments($url, $task)
    {   
        $documents = Documents::create([
            "task_id"=> $task,
            "url"=>$url,
        ]);

        $documents->save();

        return $url;

    }

    /**
     * Display the specified resource.
     */

    static function showDocumentsTask($taskId)
    {
        $documents = Documents::where("task_id",$taskId)->firstOrFail();

        return $documents->url;
    }

}
