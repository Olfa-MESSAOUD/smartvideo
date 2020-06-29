<?php

namespace content\smartvideo\Controllers;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use content\smartvideo\trax_xapiserver_statements;    

class completeController extends Controller 
{ 
    
    public function create(Request $request)
    {
      $request->validate([
        'userID' => 'required|integer|min:1',
			  'videoID' => 'required|integer|min:1',
        'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
    ]);

    $post_data = completeController::formData($request);
        $completeReq = new trax_xapiserver_statements ([
            'data' => $post_data
        ]);
        $completeReq->save();
        return response()->json([ 'message' => 'Statement added successfully' ], 201); 
    }
      
  public function list()
  {
      $result = [];
      $post = trax_xapiserver_statements::all();
      foreach($post as $statement)
      {
        if(Str::contains($statement, 'completed'))
          array_push($result, $statement);
      }
      return response()->json($result);
  }

  public function show($id)
  {
    if (trax_xapiserver_statements::where('id', $id)->exists()) {
        $statement = trax_xapiserver_statements::find($id);
        if(Str::contains($statement, 'completed'))
          return response()->json($statement);
        else
          return response()->json(['message' => 'Incorrect statement'], 400);
        } else {
          return response()->json([
            'message' => 'Statement not found'
          ], 404);
        } 
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'userID' => 'required|integer|min:1',
      'videoID' => 'required|integer|min:1',
      'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
  ]);

  if (trax_xapiserver_statements::where('id', $id)->exists()) {
    $statement = trax_xapiserver_statements::find($id);
    if(Str::contains($statement, 'completed'))
    {
        $post_data = completeController::formData($request);
        $statement->data = $post_data;
        $statement->save();
        return response()->json([
          'message' => "Statement updated successfully"
        ], 200);
      }
    else
        return response()->json(['message' => 'Incorrect statement'], 400);
    
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
          if(Str::contains($statement, 'completed'))
          {
            $statement->delete();
            return response()->json([
            'message' => 'Statement deleted'
            ], 202);
          }
        else
          return response()->json(['message' => 'Incorrect statement'], 400);
        } else {
          return response()->json([
            'message' => 'Statement not found'
          ], 404);
        }
  }

	public function formdata(Request $request)
	{	

        if ($request->timestamp == null) {
            $request->timestamp = (Carbon::now())->format('Y-m-d\TH:i:sP');
        }

		$data = [
		
		'verb' => [
			'id' => 'https://w3id.org/xapi/verbs/completed',
			'display' => [
				'en-US' => 'completed'
			],
		],
		'actor' => [
			'mbox' => 'mailto:user#'.$request->userID.'@smartvideo.fr',
			'objectType' => 'Agent'
		],
		'object' => [
			'id' => 'https://smartvideo.fr/xapi/objects/video#'.$request->videoID,
			'objectType'  => 'Activity'
		],
		'timestamp' => $request->timestamp
	
		];
    
    
    $post_data = json_encode($data, true, JSON_UNESCAPED_SLASHES);
    $result = stripslashes($post_data);
    return $result;
	}
}


