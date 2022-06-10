<?php

namespace App\Http\Controllers;

use Http;
use Excel;
use App\Http\Requests\ImportFileRequest;
use App\Imports\FileImport;
use App\Exports\FileExport;

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
        $collection = Excel::toArray(new FileImport, $file);
        $collection = array_pop($collection);
        #validacion de num de columnas
        $hasOneColum = true;
        foreach ($collection as &$dataRow) {
            $dataRow = array_filter($dataRow);
            if (sizeof($dataRow) > 1) {
                $hasOneColum = false;
            };
        }
        $collection = array_filter($collection);
        if ($hasOneColum) {
            $fileName =  basename($fileName, '.'.pathinfo($fileName, PATHINFO_EXTENSION));
            //separando el renglon de los nombres de las columnas y agregando el nombre de la segunda columna
            $collectionHeader = array_shift($collection);
            //obteniendo informacion de api
            $collection = array_map( function ($data){
                try {
                    $response = Http::withBody('{"query": "query {consultaWS(carpeta:\"'. $data[0] .'\"){ingresa}}"}', 'application/json')->post(env('ENDPOINT'))->json();
                    if ($response['data']['consultaWS']['ingresa'] === true) {
                        array_push($data, 'Carpeta  encontrada');
                    } else if($response['data']['consultaWS']['ingresa'] === false){
                        array_push($data, 'Carpeta no encontrada');
                    }
                } catch (\Throwable $th) {
                    array_push($data, 'Ocurrio un error en la consulta');
                }
                return $data;
            },$collection);
            //reintegrando la columna de los nombres de las columnas
            array_unshift($collection, $collectionHeader);
            //exportando archivo
            $export = new FileExport($collection);
            return Excel::download($export, $fileName.'.xlsx', \Maatwebsite\Excel\Excel::XLS, ['File-Name' =>  $fileName . '.xlsx']);
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
        $data = Excel::toArray(new FileImport, $file);
        $data = array_pop($data);
        #validacion de num de columnas
        $hasOneColum = true;
        foreach ($data as &$dataRow) {
            $dataRow = array_filter($dataRow);
            if (sizeof($dataRow) > 1) {
                $hasOneColum = false;
            };
        }
        $data = array_filter($data);
        if ($hasOneColum) {
            return sizeof($data) - 1;
            
        } else {
            $errors = ['errors' => ['file' => ['Please upload a file with 1 colum']]];
            return response($errors, 400);
        }
    }
}
