<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Seriesmedia;
use App\Mediaclick;

class AnalyticsController extends FrontbaseController {
    
    public function mediaclick(Request $request) {
        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $data['type'] = 'error';
            
            // MAKE VALIDATION RULES FOR RECEIVED DATA
            $rules = [
                'seriesmediaid'    => 'required'
            ];

            // VALIDATE RECEIVED DATA
            $validator = Validator::make($request->all(), $rules);

            // VALIDATION SUCCESS
            if(!$validator->fails()) {

                $seriesmediaid = intval($request->seriesmediaid);
                $seriesmedia = Seriesmedia::active()->find($seriesmediaid);

                if(!empty($seriesmedia)) {

                    $userid = isset($this->globaldata['user']) ? $this->globaldata['user']->userid : 0;
                    $mediaclick = new Mediaclick();
                    $mediaclick->seriesmediaid = $seriesmediaid;
                    $mediaclick->userid = $userid;
                    
                    if($mediaclick->save()) {
                        $data['type'] = 'success';
                    }

                }
               
            }

            return response()->json($data);

        }
        // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }
}