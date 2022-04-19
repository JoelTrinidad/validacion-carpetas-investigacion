<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* use Illuminate\Support\Facades\Validator; */
use Validator;

class CarpetasInvestigacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importFile(){
        return view('excel');
    }

    public function importExcel(Request $request){
        $validator = Validator::make($request -> all(), [
            'file' => ['required']
        ]);
        if ($validator->passes()) {

            $dataTime = date('Ymd_His');
            $file = $request -> file('file');
            $fileName = $dataTime . '-' . $file -> getClientOriginalName();
            $savePath = public_path('/upload/');
            
            $file -> move($savePath, $fileName);

            return redirect()->back()
                    ->with(['success'=>'File uploaded successfuly.']);
        } else {
            return redirect()->back()
                    ->with(['errors'=>$validator->errors()->all()]);
        }
        
    }
}
