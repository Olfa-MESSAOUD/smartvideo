<?php

namespace content\smartvideo\Controllers;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use content\smartvideo\trax_xapiserver_statements; 

class terminateController extends Controller
{
    public function create(Request $request)
    {
      $request->validate([
        'userID' => 'required|integer|min:1',
			  'videoID' => 'required|integer|min:1',
        'position' => 'required|regex:/^PT(\d+H)?(\d+M)?(\d+S)?$/',
        'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
      ]);

    $post_data = terminateController::formData($request);
    $statement = new trax_xapiserver_statements ([
      'data' => $post_data
    ]);
    $statement->save();
    return response()->json([ 'message' => 'Statement added successfully' ], 201); 
      }

      public function list()
      {
          $result = [];
          $post = trax_xapiserver_statements::all();
          foreach($post as $statement)
          {
            if(Str::contains($statement, 'terminated'))
              array_push($result, $statement);
          }
          return response()->json($result);
      }
  
      public function show($id)
      {
        if (trax_xapiserver_statements::where('id', $id)->exists()) {
            $statement = trax_xapiserver_statements::find($id);
            if(Str::contains($statement, 'terminated'))
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
          'position' => 'required|regex:/^PT(\d+H)?(\d+M)?(\d+S)?$/',
          'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
      ]);
  
      if (trax_xapiserver_statements::where('id', $id)->exists()) {
        $statement = trax_xapiserver_statements::find($id);
        if(Str::contains($statement, 'terminated'))
        {
            $post_data = terminateController::formData($request);
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
          if(Str::contains($statement, 'terminated'))
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

	public function formData(Request $request)
	{

        if ($request->timestamp == null) {
            $request->timestamp = (Carbon::now())->format('Y-m-d\TH:i:sP');
        }

		$data = [

		'verb' => [
			'id' => 'https://w3id.org/xapi/verbs/terminated',
			'display' => [
				'en-US' => 'terminated'
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
		'context' => [
			'extensions' => [
                "https://smartvideo.fr/xapi/extensions/position" => $request->position
			]
        ],
		'timestamp' => $request->timestamp

		];

    $post_data = json_encode($data, true, JSON_UNESCAPED_SLASHES);
    $result = stripslashes($post_data);
    return $result;
	}

}
