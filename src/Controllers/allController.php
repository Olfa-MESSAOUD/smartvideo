<?php

namespace content\smartvideo\Controllers;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use content\smartvideo\trax_xapiserver_statements;

class allController extends Controller
{
      public function list()
    {
        $post = trax_xapiserver_statements::all();
        return response()->json($post);
    }

    public function show($id)
    {
      if (trax_xapiserver_statements::where('id', $id)->exists()) {
          $statement = trax_xapiserver_statements::find($id);
            return response()->json($statement);
          } else {
            return response()->json([
              'message' => 'Statement not found'
            ], 404);
          } 
    }

    public function destroy($id)
    {
        if(trax_xapiserver_statements::where('id', $id)->exists()) {
            $statement = trax_xapiserver_statements::where('id','=',$id)->first();
            $statement->delete();
            return response()->json([
              'message' => 'Statement deleted'
              ], 202);
         } else {
            return response()->json([
              'message' => 'Statement not found'
            ], 404);
          }
    }
}
