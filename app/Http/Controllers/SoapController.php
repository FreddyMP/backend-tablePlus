<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoapController extends Controller
{
    protected $soap;

    public function __construct()
    {
        $this->soap = new \SoapClient("http://example.com/soap?wsdl");
    }

    public function store(Request $request)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $response = $this->soap->__soapCall('createTask', [$data]);

        return response()->json($response);
    }

    public function show($id)
    {
        $result = $this->soap->__soapCall('showTask', [['id' => $id]]);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'id' => $id,
            'title' => $request->title,
            'descripcion' => $request->description,
            'date_end' => $request->date_end,
            'hour_end' => $request->date_end,
        ];

        $result = $this->soap->__soapCall('updateTask', [$data]);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = $this->soap->__soapCall('deleteTask', [['id' => $id]]);
        return response()->json($result);
    }
}
