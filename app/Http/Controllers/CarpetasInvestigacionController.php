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
        if (sizeof($collection[1]) == 1) {
            $numRows = sizeof($collection);
            $fileName =  basename($fileName, '.'.pathinfo($fileName, PATHINFO_EXTENSION));
            $downloadPath = public_path('/download/');
            //obteniendo informacion de api
            $response = Http::get('https://jsonplaceholder.typicode.com/posts/1/comments');
            $httpData = array_map( function ($data){ return $data['body'];},$response->json());
            //agregando nombre de columna a la informacion de la api
            array_unshift($httpData, 'Carpeta de Inversigación');
            //incorporando información de la api a la información del archivo
            for ($row=0; $row < $numRows; $row++) { 
                $collection[$row][]= $httpData[$row];
            }
            $newCollection = collect($collection);
            //creando nuevo archivo para exportar
            $excel = Exporter::make('Excel');
            $excel->load($newCollection);
            $excel->save($downloadPath . $fileName .'.xlsx');
            // exportando archivo
            return response()->download($downloadPath . $fileName .'.xlsx', $fileName.'.xlsx', ['File-Name' =>  $fileName . '.xlsx'])->deleteFileAfterSend();
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
            $errors = ['errors' => ['file' => ['Please upload a file with 1 colum']]];
            return response($errors, 400);
        }
        
    }
}
