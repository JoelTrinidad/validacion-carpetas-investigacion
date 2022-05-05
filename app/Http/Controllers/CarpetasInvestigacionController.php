<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http;
/* use Illuminate\Support\Facades\Validator; */
use Validator;
use Importer;
use Exporter;
use Excel;

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
                /* for ($row=1; $row < $numRows; $row++) { 
                    try {
                        var_dump($collection[$row]);
                    } catch (\Exception $e) {
                        return redirect()->back()
                                ->with(['errors'=>$e -> getMessage()]);
                    }
                } */
/*                 $response = Http::withHeaders([
                    'Content-type' => 'application/json; charset=UTF-8',
                ])->post('https://jsonplaceholder.typicode.com/posts', [
                    'title' => 'foo',
                    'body' => 'bar',
                    'userId' => 1,
                ]); */
                return 'hola mundo';
                /* $response = Http::withHeaders([
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Connection' => 'keep-alive',
                    'DNT' => '1',
                    'Origin' => 'http://10.250.109.35:8000'
                ])->post('http://10.250.109.35:8000/graphql', [
                    'query' => 'query {consultaWS(carpeta:\"CI-FIIZP/IZP-6/UI-2 C/D/05211/10-2021\"){ingresa}}',
                ]); */
            } else {
                return redirect()->back()
                        ->with(['errors'=>[0 => 'Please provide data in file no more than 1 colum.']]);
            }
            
        } else {
            return redirect()->back()
                    ->with(['errors'=>$validator->errors()->all()]);
        }
        
    }

    public function ajax(Request $request){
        $savePath = public_path('/upload/');
        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1/comments');
        $httpData = array_map( function ($data){ return $data['body'];},$response->json());
        array_unshift($httpData, 'Carpeta de Inversigación');
        $excel = Importer::make('Excel');
        $excel -> load($savePath . '20220504_203944-Carpeta investigacion prueba.xlsx');
        $excelData = $excel->getCollection()->toArray();
        for ($row=0; $row < sizeof($excelData); $row++) { 
            $excelData[$row][]= $httpData[$row];
        }
        $collection = collect($excelData);
        $excel = Exporter::make('Csv');
        $excel->load($collection);
        return $excel->stream('20220504_203944-Carpeta investigacion prueba.csv');
        
    }
}
