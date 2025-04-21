<?php

use Illuminate\http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoapController;

Route::get('/soap', function () {
    $soap = new SoapController();

    $result = $soap->create();
    return $result;
});
