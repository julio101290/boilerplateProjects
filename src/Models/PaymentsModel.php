<?php

namespace julio101290\boilerplatesells\Models;

use CodeIgniter\Model;

class PaymentsModel extends Model {

    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idSell'
        , 'importPayment'
        , 'importBack'
        , 'datePayment'
        , 'metodPayment'
        , 'idComplemento'
        , 'observaciones'
        , 'idNotaCredito'
        , 'tipo'
        , 'created_at'
        , 'updated_at'
        , 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idSell' => 'required|is_natural_no_zero',
        'importPayment' => 'required|decimal',
        'importBack' => 'required|decimal',
        'datePayment' => 'required|valid_date[Y-m-d H:i:s]',
        'metodPayment' => 'required|string|max_length[32]',
        'idComplemento' => 'required|is_natural',
        'observaciones' => 'permit_empty|string|max_length[2048]',
        'idNotaCredito' => 'permit_empty|is_natural',
        'tipo' => 'permit_empty|string|max_length[5]',
    ];
    protected $validationMessages = [
        'idSell' => [
            'required' => 'El campo "Venta relacionada" es obligatorio.',
            'is_natural_no_zero' => 'Debe ser un número entero mayor a cero.'
        ],
        'importPayment' => [
            'required' => 'El importe del pago es obligatorio.',
            'decimal' => 'Debe ser un valor decimal válido.'
        ],
        'importBack' => [
            'required' => 'El importe de cambio es obligatorio.',
            'decimal' => 'Debe ser un valor decimal válido.'
        ],
        'datePayment' => [
            'required' => 'La fecha de pago es obligatoria.',
            'valid_date' => 'La fecha de pago debe tener el formato YYYY-MM-DD HH:MM:SS.'
        ],
        'metodPayment' => [
            'required' => 'El método de pago es obligatorio.',
            'max_length' => 'Máximo 32 caracteres.'
        ],
        'idComplemento' => [
            'required' => 'El complemento es obligatorio.',
            'is_natural' => 'Debe ser un número entero positivo o cero.'
        ],
        'observaciones' => [
            'max_length' => 'Las observaciones no deben superar los 2048 caracteres.'
        ],
        'idNotaCredito' => [
            'is_natural' => 'Debe ser un número válido o estar vacío.'
        ],
        'tipo' => [
            'max_length' => 'Máximo 5 caracteres.'
        ]
    ];
    protected $skipValidation = false;
}
