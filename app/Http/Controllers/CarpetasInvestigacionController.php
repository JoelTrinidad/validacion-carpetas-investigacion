<?php

namespace App\Http\Controllers;

use Http;
use Importer;
use Exporter;
use File;
use App\Http\Requests\ImportFileRequest;

class CarpetasInvestigacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importFile(){
        return view('excel');
    }

    public function importExcel(ImportFileRequest $request){
        #guardo archivo
        $dataTime = date('Ymd_His');
        $file = $request -> file('file');
        $fileName = $dataTime . '-' . $file -> getClientOriginalName();
        $savePath = public_path('/upload/');
        $file -> move($savePath, $fileName);
        #leyendo archivo
        $excel = Importer::make('Excel');
        $excel -> load($savePath . $fileName);
        $collection = $excel->getCollection()->toArray();
        //eliminando archivo importado en upload
        if(File::exists($savePath . $fileName)){
            File::delete($savePath . $fileName);
        }
        #validacion de num de columnas
        $hasOneColum = true;
        foreach ($collection as &$dataRow) {
            $dataRow = array_filter($dataRow);
            if (sizeof($dataRow) !== 1) {
                $hasOneColum = false;
                break;
            };
        }
        if ($hasOneColum) {
            $fileName =  basename($fileName, '.'.pathinfo($fileName, PATHINFO_EXTENSION));
            $downloadPath = public_path('/download/');
            //separando el renglon de los nombres de las columnas y agregando el nombre de la segunda columna
            $collectionHeader = array_shift($collection);
            array_push($collectionHeader, 'Carpeta de Inversigación');
            //obteniendo informacion de api
            $collection = array_map( function ($data){
                $response = Http::withBody('{"query": "query {consultaWS(carpeta:\"'. $data[0] .'\"){ingresa}}"}', 'application/json')->post(env('ENDPOINT'))->json();
                if ($response['data']['consultaWS']['ingresa'] === true) {
                    array_push($data, 'Carpeta  encontrada');
                } else if($response['data']['consultaWS']['ingresa'] === false){
                    array_push($data, 'Carpeta no encontrada');
                }
                return $data;
            },$collection);
            //reintegrando la columna de los nombres de las columnas
            array_unshift($collection, $collectionHeader);
            //creando nuevo archivo para exportar
            $newCollection = collect($collection);
            $excel = Exporter::make('Excel');
            $excel->load($newCollection);
            $excel->save($downloadPath . $fileName .'.xlsx');
            // exportando archivo
            return response()->download($downloadPath . $fileName .'.xlsx', $fileName.'.xlsx', ['File-Name' =>  $fileName . '.xlsx'])->deleteFileAfterSend();
        } else {
            $errors = ['errors' => ['file' => ['Please upload a file with 1 colum']]];
            return response($errors, 400);
        }
    }

    public function timeout(ImportFileRequest $request){
                #guardo archivo
                $dataTime = date('Ymd_His');
                $file = $request -> file('file');
                $fileName = $dataTime . '-' . $file -> getClientOriginalName();
                $savePath = public_path('/upload/');
                $file -> move($savePath, $fileName);
                #leyendo archivo
                $excel = Importer::make('Excel');
                $excel -> load($savePath . $fileName);
                $data = $excel->getCollection()->toArray();
                //eliminando archivo importado en upload
                if(File::exists($savePath . $fileName)){
                    File::delete($savePath . $fileName);
                }
                #validacion de num de columnas
                $hasOneColum = true;
                foreach ($data as &$dataRow) {
                    $dataRow = array_filter($dataRow);
                    if (sizeof($dataRow) !== 1) {
                        $hasOneColum = false;
                        break;
                    };
                }
                if ($hasOneColum) {
                    return sizeof($data) - 1;
                    
                } else {
                    $errors = ['errors' => ['file' => ['Please upload a file with 1 colum']]];
                    return response($errors, 400);
                }
    }
}
