<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* use Illuminate\Support\Facades\Validator; */
use Validator;
use Importer;

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
            #guardo archivo
            $dataTime = date('Ymd_His');
            $file = $request -> file('file');
            $fileName = $dataTime . '-' . $file -> getClientOriginalName();
            $savePath = public_path('/upload/');
            $file -> move($savePath, $fileName);
            #leyendo archivo
            $excel = Importer::make('Excel');
            $excel -> load($savePath . $fileName);
            $collection = $excel->getCollection();
            #validacion de num de columnas
            if (sizeof($collection[1]) == 1) {
                $numRows = sizeof($collection);
                for ($row=1; $row < $numRows; $row++) { 
                    try {
                        var_dump($collection[$row]);
                    } catch (\Exception $e) {
                        return redirect()->back()
                                ->with(['errors'=>$e -> getMessage()]);
                    }
                }
            } else {
                return redirect()->back()
                        ->with(['errors'=>[0 => 'Please provide data in file no more than 1 colum.']]);
            }
            
        } else {
            return redirect()->back()
                    ->with(['errors'=>$validator->errors()->all()]);
        }
        
    }
}
