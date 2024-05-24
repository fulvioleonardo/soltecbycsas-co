<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted' => ':attribute debe ser aceptado.',
    'active_url' => ':attribute no es una URL válida.',
    'after' => ':attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => ':attribute sólo debe contener letras.',
    'alpha_dash' => ':attribute sólo debe contener letras, números y guiones.',
    'alpha_num' => ':attribute sólo debe contener letras y números.',
    'array' => ':attribute debe ser un conjunto.',
    'before' => ':attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => ':attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => ':attribute tiene que estar entre :min - :max.',
        'file' => ':attribute debe pesar entre :min - :max kilobytes.',
        'string' => ':attribute tiene que tener entre :min - :max caracteres.',
        'array' => ':attribute tiene que tener entre :min - :max ítems.',
    ],
    'boolean' => 'El campo :attribute debe tener un valor verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => ':attribute no es una fecha válida.',
    'date_format' => ':attribute no corresponde al formato :format.',
    'different' => ':attribute y :other deben ser diferentes.',
    'digits' => ':attribute debe tener :digits dígitos.',
    'digits_between' => ':attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct' => 'El campo :attribute contiene un valor duplicado.',
    'email' => ':attribute no es un correo válido',
    'exists' => ':attribute es inválido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute es obligatorio.',
    'image' => ':attribute debe ser una imagen.',
    'in' => ':attribute es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => ':attribute debe ser un número entero.',
    'ip' => ':attribute debe ser una dirección IP válida.',
    'ipv4' => ':attribute debe ser un dirección IPv4 válida',
    'ipv6' => ':attribute debe ser un dirección IPv6 válida.',
    'json' => 'El campo :attribute debe tener una cadena JSON válida.',
    'max' => [
        'numeric' => ':attribute no debe ser mayor a :max.',
        'file' => ':attribute no debe ser mayor que :max kilobytes.',
        'string' => ':attribute no debe ser mayor que :max caracteres.',
        'array' => ':attribute no debe tener más de :max elementos.',
    ],
    'mimes' => ':attribute debe ser un archivo con formato: :values.',
    'mimetypes' => ':attribute debe ser un archivo con formato: :values.',
    'min' => [
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'file' => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'string' => ':attribute debe contener al menos :min caracteres.',
        'array' => ':attribute debe tener al menos :min elementos.',
    ],
    'not_in' => ':attribute es inválido.',
    'numeric' => ':attribute debe ser numérico.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El formato de :attribute es inválido.',
    'required' => 'El campo :attribute es obligatorio.',
    'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values estén presentes.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'file' => 'El tamaño de :attribute debe ser :size kilobytes.',
        'string' => 'El campo :attribute debe contener :size caracteres.',
        'array' => ':attribute debe contener :size elementos.',
    ],
    'string' => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone' => 'El :attribute debe ser una zona válida.',
    'unique' => ':attribute ya ha sido registrado.',
    'uploaded' => 'Subir :attribute ha fallado.',
    'url' => 'El formato :attribute es inválido.',

    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password' => [
            'min' => 'La :attribute debe contener más de :min caracteres',
        ],
        'email' => [
            'unique' => 'El :attribute ya ha sido registrado.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'nombre',
        'username' => 'usuario',
        'email' => 'correo electrónico',
        'first_name' => 'nombre',
        'last_name' => 'apellido',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de la contraseña',
        'city' => 'ciudad',
        'country' => 'país',
        'address' => 'dirección',
        'phone' => 'teléfono',
        'mobile' => 'móvil',
        'age' => 'edad',
        'sex' => 'sexo',
        'gender' => 'género',
        'year' => 'año',
        'month' => 'mes',
        'day' => 'día',
        'hour' => 'hora',
        'minute' => 'minuto',
        'second' => 'segundo',
        'title' => 'título',
        'content' => 'contenido',
        'body' => 'contenido',
        'description' => 'descripción',
        'excerpt' => 'extracto',
        'date' => 'fecha',
        'time' => 'hora',
        'subject' => 'asunto',
        'message' => 'mensaje',
        'soap_type_id' => 'SOAP tipo',
        'soap_username' => 'SOAP Usuario',
        'soap_password' => 'SOAP Contraseña',
        'company_number' => 'número de empresa',
        'company_name' => 'nombre de empresa',
        'unit_price' => 'Precio unitario' ,
        'bank_id' => 'banco',
        'number' => 'número',
        'currency_type_id' => 'moneda',
        'trade_name' => 'nombre comercial',
        'identity_document_type_id' => 'tipo de documento de identidad',
        'department_id' => 'departamento',
        'province_id' => 'provincia',
        'district_id' => 'distrito',
        'customer_email' => 'correo del cliente',
        'customer_id' => 'cliente',
        'voided_description' => 'descripción del motivo de anulación',
        'code' => 'código',
        'unit_type_id' => 'unidad',
        'document_type_id' => 'tipo de documento',
        'supplier_id' => 'proveedor',
        'date_of_issue' => 'fecha de emisión',
        'charge_discount_type_id' => 'tipo de cargo',
        'pricing' => 'precio',
        'limit_users' => 'límite de usuarios',
        'limit_documents' => 'límite de documentos',
        'plan_documents' => 'documentos activos',
        'plan_id' => 'plan',

        'note.note_credit_type_id' => 'tipo de nota de crédito',
        'note.note_debit_type_id' => 'tipo de nota de débito',
        'note.note_description' => 'descripción',
        'exchange_rate_sale' => 'tipo de cambio',
        'type' => 'perfil',
        'item_id' => 'producto',
        'warehouse_id' => 'almacén',
        'inventory_transaction_id' => 'motivo traslado',
        'quantity' => 'cantidad',
        'category_id' => 'categoría',
        'brand_id' => 'marca',
        
        'room_type' => 'tipo de habitación',
        'ocupation' => 'ocupación',
        'sex' => 'sexo',
        'age' => 'edad',
        'civil_status' => 'estado civil',
        'nacionality' => 'nacionalidad',
        'origin' => 'procedencia',
        'room_number' => 'N° de habitación',
        'date_entry' => 'fecha ingreso',
        'time_entry' => 'hora ingreso',
        'date_exit' => 'fecha salida',
        'time_exit' => 'hora salida',
        'person_type_id' => 'tipo de cliente',
        'customers' => 'cliente',
        'sale_unit_price' => 'precio unitario de venta',
        'transport_mode_type_id' => 'modo de translado',
        'delivery.address' => 'dirección',
        'origin.address' => 'dirección',
        'transfer_reason_type_id' => 'motivo de translado',
        'token_server' => 'token servidor',
        'url_server' => 'url servidor',
        'is_client' => 'modo offline',
        'series_id' => 'series',
        'observations' => 'observaciones',
        'operation_type_id' => 'tipo operación',
        'percentage ' => 'porcentaje',
        'start_number ' => 's',
        'commission_amount' => 'monto de comisión',


        'number_identity_document' => 'número documento',
        'passenger_fullname' => 'nombre y apellidos',
        'passenger_manifest' => 'manifiesto pasajeros',
        'seat_number' => 'número asiento',
        'start_date' => 'fecha inicio',
        'start_time' => 'hora inicio',
        'origin_address' => 'dirección origen',
        'origin_district_id' => 'ubigeo',
        'destinatation_district_id' => 'ubigeo',
        'destinatation_address' => 'dirección destino',
        'date_start' => 'fecha inicial',
        'date_end' => 'fecha final',
        'payment_method_type_id' => 'm. pago',
        'expense_method_type_id' => 'm. gasto',
        'payment_destination_id' => 'destino',
        'telephone' => 'teléfono',
        'customer' => 'cliente',

        'cellphone' => 'celular',
        'state' => 'estado',
        'reason' => 'motivo',
        'serial_number' => 'n° serie',
        'prepayment' => 'pago adelantado',
        'activities' => 'actividades',
        'user_id' => 'vendedor',
        'internal_id' => 'código interno',


        'identification_number' => 'número de identificación',
        'subdomain' => 'subdominio',
        'economic_activity_code' => 'actividad económica',
        'ica_rate' => 'tasa ICA',
        'type_document_identification_id' => 'tipo documentación',
        'municipality_id' => 'municipio',
        'type_organization_id' => 'tipo Organizacion',
        'type_regime_id' => 'regimen',
        'merchant_registration' => 'registro mercantil',
        'currency_id' => 'moneda',
        

        
        'name'                  => 'nombre',
        'username'              => 'usuario',
        'email'                 => 'correo electrónico',
        'first_name'            => 'nombre',
        'last_name'             => 'apellido',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de la contraseña',
        'city'                  => 'ciudad',
        'country'               => 'país',
        'address'               => 'dirección',
        'phone'                 => 'teléfono',
        'mobile'                => 'móvil',
        'age'                   => 'edad',
        'sex'                   => 'sexo',
        'gender'                => 'género',
        'year'                  => 'año',
        'month'                 => 'mes',
        'day'                   => 'día',
        'hour'                  => 'hora',
        'minute'                => 'minuto',
        'second'                => 'segundo',
        'title'                 => 'título',
        'content'               => 'contenido',
        'body'                  => 'contenido',
        'description'           => 'descripción',
        'excerpt'               => 'extracto',
        'date'                  => 'fecha',
        'time'                  => 'hora',
        'subject'               => 'asunto',
        'message'               => 'mensaje',

        'idpayroll'               => 'id software nómina',
        'pinpayroll'               => 'pin software nómina',

        'surname' => 'apellido',
        'second_surname' => 'segundo apellido',
        'type_worker_id' => 'tipo empleado',
        'sub_type_worker_id' => 'subtipo empleado',
        'payroll_type_document_identification_id' => 'tipo de identificación',
        'type_contract_id' => 'tipo contrato',
        'salary' => 'salario',
        'payroll_period_id' => 'periodo de nómina',
        'worker_id' => 'empleado',

        'period.issue_date' => 'fecha emisión',
        'period.admision_date' => 'fecha admisión',
        'period.settlement_start_date' => 'fecha de inicio de liquidación',
        'period.settlement_end_date' => 'fecha de finalización de liquidación',
        'period.worked_time' => 'tiempo trabajado',

        'payment.payment_method_id' => 'método de pago',
        'payment.bank_name' => 'nombre del banco',
        'payment.account_type' => 'tipo de cuenta',
        'payment.account_number' => 'número de cuenta',
        'payment_dates' => 'fechas de pago',

        'accrued.worked_days' => 'días trabajados',
        'accrued.salary' => 'salario',
        'accrued.accrued_total' => 'total devengados',
        
        'deduction.eps_type_law_deductions_id' => 'EPS - Deducciones por ley',
        'deduction.eps_deduction' => 'deducción EPS',
        'deduction.pension_type_law_deductions_id' => 'Pensión - Deducciones por ley',
        'deduction.pension_deduction' => 'deducción de pensión',
        'deduction.deductions_total' => 'deducción Total',
        'work_start_date' => 'fecha inicio de labores',

        'accrued.service_bonus.*.quantity' => 'n° de días',
        'accrued.service_bonus.*.payment' => 'prima salarial',
        'accrued.service_bonus.*.paymentNS' => 'prima no salarial',

        'accrued.severance.*.payment' => 'pago cesantías',
        'accrued.severance.*.percentage' => '% interes',
        'accrued.severance.*.interest_payment' => 'pago intereses',
        
        'accrued.bonuses.*.salary_bonus' => 'bonificación salarial',
        'accrued.bonuses.*.non_salary_bonus' => 'bonificación no salarial',

        'accrued.aid.*.salary_assistance' => 'ayuda salarial',
        'accrued.aid.*.non_salary_assistance' => 'ayuda no salarial',

        'accrued.other_concepts.*.salary_concept' => 'salarial',
        'accrued.other_concepts.*.non_salary_concept' => 'no salarial',
        'accrued.other_concepts.*.description_concept' => 'concepto',

        'accrued.common_vacation.*.start_date' => 'fecha inicio',
        'accrued.common_vacation.*.end_date' => 'fecha término',
        'accrued.common_vacation.*.quantity' => 'n° de días',
        'accrued.common_vacation.*.payment' => 'pago',
        
        'accrued.paid_vacation.*.start_date' => 'fecha inicio',
        'accrued.paid_vacation.*.end_date' => 'fecha término',
        'accrued.paid_vacation.*.quantity' => 'n° de días',
        'accrued.paid_vacation.*.payment' => 'pago',

        'accrued.work_disabilities.*.start_date' => 'fecha inicio',
        'accrued.work_disabilities.*.end_date' => 'fecha término',
        'accrued.work_disabilities.*.quantity' => 'cantidad',
        'accrued.work_disabilities.*.type' => 'tipo',
        'accrued.work_disabilities.*.payment' => 'pago',

        'deduction.labor_union.*.percentage' => 'porcentaje',
        'deduction.labor_union.*.deduction' => 'deducción',
        
        'deduction.sanctions.*.public_sanction' => 'sanción pública',
        'deduction.sanctions.*.private_sanction' => 'sanción privada',

        'accrued.maternity_leave.*.start_date' => 'fecha inicio',
        'accrued.maternity_leave.*.end_date' => 'fecha término',
        'accrued.maternity_leave.*.quantity' => 'cantidad',
        'accrued.maternity_leave.*.payment' => 'pago',

        'accrued.paid_leave.*.start_date' => 'fecha inicio',
        'accrued.paid_leave.*.end_date' => 'fecha término',
        'accrued.paid_leave.*.quantity' => 'cantidad',
        'accrued.paid_leave.*.payment' => 'pago',
        
        'accrued.non_paid_leave.*.start_date' => 'fecha inicio',
        'accrued.non_paid_leave.*.end_date' => 'fecha inicio',
        'accrued.non_paid_leave.*.quantity' => 'cantidad',

        'accrued.commissions.*.commission' => 'comisión',

        'accrued.epctv_bonuses.*.paymentS' => 'pago salarial',
        'accrued.epctv_bonuses.*.paymentNS' => 'pago no salarial',
        'accrued.epctv_bonuses.*.salary_food_payment' => 'pago alimentacion salarial',
        'accrued.epctv_bonuses.*.non_salary_food_payment' => 'pago alimentacion no salarial',

        'accrued.third_party_payments.*.third_party_payment' => 'pago',

        'accrued.advances.*.advance' => 'valor anticipo',
        
        'accrued.compensations.*.ordinary_compensation' => 'compensación ordinaria',
        'accrued.compensations.*.extraordinary_compensation' => 'compensación extraordinaria',

        'accrued.legal_strike.*.start_date' => 'fecha inicio',
        'accrued.legal_strike.*.end_date' => 'fecha inicio',
        'accrued.legal_strike.*.quantity' => 'cantidad',

        'accrued.endowment' => 'dotación',
        'accrued.sustenance_support' => 'apoyo de sustento',
        'accrued.telecommuting' => 'teletrabajo',
        'accrued.withdrawal_bonus' => 'bono de retiro',
        'accrued.compensation' => 'indemnización',

        'deduction.orders.*.description' => 'descripción',
        'deduction.orders.*.deduction' => 'deducción',
        
        'deduction.third_party_payments.*.third_party_payment' => 'valor pago',

        'deduction.advances.*.advance' => 'valor anticipo',

        'deduction.other_deductions.*.other_deduction' => 'deducción',

        // opcionales
        'deduction.voluntary_pension' => 'pensión voluntaria',
        'deduction.withholding_at_source' => 'retención fuente',
        'deduction.afc' => 'afc',
        'deduction.cooperative' => 'cooperativa',
        'deduction.tax_liens' => 'embargo fiscal',
        'deduction.supplementary_plan' => 'plan complementarios',
        'deduction.education' => 'educación',
        'deduction.refund' => 'reintegro',
        'deduction.debt' => 'deuda',

        // opcionales
        'accrued.transportation_allowance' => 'subsidio de transporte',
        'accrued.telecommuting' => 'teletrabajo',
        'accrued.endowment' => 'dotación',
        'accrued.sustenance_support' => 'apoyo de sustento',
        'accrued.withdrawal_bonus' => 'bono de retiro',
        'accrued.compensation' => 'indemnización',
        
        'deduction.fondossp_type_law_deductions_id' => 'fondo de seguridad pensional',
        'deduction.fondosp_deduction_SP' => 'deducción de fondo SP',
        
        'deduction.fondossp_sub_type_law_deductions_id' => 'fondo de subsistencia',
        'deduction.fondosp_deduction_sub' => 'deducción de fondo subsistencia',

        'accrued.salary_viatics' => 'manutención y/o alojamiento',
        'accrued.non_salary_viatics' => 'manutención y/o alojamiento no salariales',
        'accrued.refund' => 'reintegro',

        'resolution_id' => 'resolución',
        'accrued.total_base_salary' => 'salario base',
        'postal_code' => 'código postal',
        'note_concept_id' => 'concepto',
        'discrepancy_response_description' => 'descripción de la corrección',

    ],
];
