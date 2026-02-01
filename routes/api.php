<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LabIntegrationController;

Route::post('/lab/receive', [LabIntegrationController::class, 'receive']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/fhir/DiagnosticReport', [\App\Http\Controllers\Api\FhirController::class, 'storeDiagnosticReport']);
