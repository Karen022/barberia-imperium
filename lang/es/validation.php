<?php

return [

    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'regex' => 'El campo :attribute tiene un formato inválido.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
        'integer' => 'El campo :attribute debe ser como mínimo :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'file' => 'El archivo :attribute no puede superar los :max kilobytes.',
    ],
    'image' => 'El campo :attribute debe ser una imagen.',
    'confirmed' => 'La confirmación del :attribute no coincide.',
    'current_password' => 'La contraseña actual no es correcta.',

    'attributes' => [
        'name' => 'nombre',
        'phone' => 'teléfono',
        'description' => 'descripción',
        'price' => 'precio',
        'stock' => 'cantidad',
        'image' => 'imagen',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'document_type' => 'tipo de documento',
        'document_number' => 'número de documento',
    ],

];