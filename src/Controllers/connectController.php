<?php


namespace content\smartvideo\Controllers;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stevebauman\Location;
use content\smartvideo\trax_xapiserver_statements;  

class connectController extends Controller
{  
    public function create(Request $request)
    {
      $request->validate([
        'userID' => 'required|integer|min:1',
        'latitude' => 'bail|nullable|numeric|required_with:longitude',
        'longitude' => 'bail|nullable|numeric|required_with:latitude', 
        'ipAddress' => 'bail|nullable|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',
        'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
    ]);
        
    $post_data = connectController::formData($request);
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
          if(Str::contains($statement, 'connected'))
            array_push($result, $statement);
        }
        return response()->json($result);
    }
  
    public function show($id)
    {
      if (trax_xapiserver_statements::where('id', $id)->exists()) {
          $statement = trax_xapiserver_statements::find($id);
          if(Str::contains($statement, 'connected'))
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
        'latitude' => 'bail|nullable|numeric|required_with:longitude',
        'longitude' => 'bail|nullable|numeric|required_with:latitude', 
        'ipAddress' => 'bail|nullable|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',
        'timestamp' => 'bail|nullable|date_format:Y-m-d\TH:i:sP'
    ]);

     if (trax_xapiserver_statements::where('id', $id)->exists()) {
      $statement = trax_xapiserver_statements::find($id);
      if(Str::contains($statement, 'connected'))
      {
          $post_data = connectController::formData($request);
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
        if(Str::contains($statement, 'connected'))
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


     if($request->ipAddress == null)
     {

        $data = [
          'verb' => [
          'id' => 'https://smartvideo.fr/xapi/verbs/connected',
          'display' => [
            'en-US' => 'connected'
          ],
        ],
        'actor' => [
          'mbox' => 'mailto:user#'.$request->userID.'@smartvideo.fr',
          'objectType' => 'Agent'
        ],
        'object' => [
          'id' => 'https://smartvideo.fr/xapi/objects/void',
          'objectType'  => 'Activity'
        ],
        'context' => [
          'extensions' => [
                    'http://id.tincanapi.com/extension/geojson' => [
                        'type' => 'Feature',
                        'geometry' => [
                          'type' => 'Point',
                          'coordinates' => array_values([(float)$request->longitude, (float)$request->latitude])                         
                          ] 
                      ]
                  ]
        ],
        'timestamp' => $request->timestamp

        ];
     }
     else
     {
      $data = [
        'verb' => [
        'id' => 'https://smartvideo.fr/xapi/verbs/connected',
        'display' => [
          'en-US' => 'connected'
        ],
      ],
      'actor' => [
        'mbox' => 'mailto:user#'.$request->userID.'@smartvideo.fr',
        'objectType' => 'Agent'
      ],
      'object' => [
        'id' => 'https://smartvideo.fr/xapi/objects/void',
        'objectType'  => 'Activity'
      ],
      'context' => [
        'extensions' => [
                  'http://id.tincanapi.com/extension/ip-address' => $request->ipAddress
                    ]        
      ],
      'timestamp' => $request->timestamp
     ];
      
    }

    
    $post_data = json_encode($data, true, JSON_UNESCAPED_SLASHES);
    $result = stripslashes($post_data);
    return $result;
  }

}


