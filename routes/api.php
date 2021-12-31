<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/ping', function(){
  return ['pong' => true];
});

Route::post('/upload', function(Request $request){
  $array = ['error' => ''];

  $rules = [// Regras para validação
    'name' => 'required|min:2',
    'foto' => 'required|mimes:jpg,png'
  ];
  // Processo de validação
  $validator = Validator::make($request->all(), $rules);
  if($validator->fails()) { 
    $array['error'] = $validator->messages();
    return $array;
  }

  if($request->hasFile('foto')) {// Se tem arquivo 'teste'
      if($request->file('foto')->isValid()) {// Se é válido o arquivo

        $foto = $request->file('foto')->store('public');

        $url = asset(Storage::url($foto));
        //echo $url;
        $array['url'] = $url;

      }
  } else {
      $array['error'] = 'Não foi enviado arquivo!';
  }

  return $array;
});
