<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El :attribute debe ser aceptado.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after' => 'El :attribute must be a date after :date.',
    'after_or_equal' => 'El :attribute must be a date after or equal to :date.',
    'alpha' => 'El :attribute may only contain letters.',
    'alpha_dash' => 'El :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'El :attribute may only contain letters and numbers.',
    'array' => 'El :attribute must be an array.',
    'before' => 'El :attribute must be a date before :date.',
    'before_or_equal' => 'El :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'El :attribute must be between :min and :max.',
        'file' => 'El :attribute must be between :min and :max kilobytes.',
        'string' => 'El :attribute must be between :min and :max characters.',
        'array' => 'El :attribute must have between :min and :max items.',
    ],
    'boolean' => 'El :attribute el campo debe ser verdadero o falso.',
    'confirmed' => 'El :attribute la confirmación no coincide.',
    'date' => 'El :attribute no es una fecha válida.',
    'date_equals' => 'El :attribute must be a date equal to :date.',
    'date_format' => 'El :attribute does not match the format :format.',
    'different' => 'El :attribute and :other must be different.',
    'digits' => 'El :attribute must be :digits digits.',
    'digits_between' => 'El :attribute must be between :min and :max digits.',
    'dimensions' => 'El :attribute has invalid image dimensions.',
    'distinct' => 'El :attribute field has a duplicate value.',
    'email' => 'El :attribute must be a valid email address.',
    'ends_with' => 'El :attribute must end with one of the following: :values.',
    'exists' => 'El seleccionado :attribute es invalido.',
    'file' => 'El :attribute must be a file.',
    'filled' => 'El :attribute field must have a value.',
    'gt' => [
        'numeric' => 'El :attribute must be greater than :value.',
        'file' => 'El :attribute must be greater than :value kilobytes.',
        'string' => 'El :attribute must be greater than :value characters.',
        'array' => 'El :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'El :attribute must be greater than or equal :value.',
        'file' => 'El :attribute must be greater than or equal :value kilobytes.',
        'string' => 'El :attribute must be greater than or equal :value characters.',
        'array' => 'El :attribute must have :value items or more.',
    ],
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El seleccionado :attribute Es invalido.',
    'in_array' => 'El :attribute field does not exist in :other.',
    'integer' => 'El :attribute debe ser un entero.',
    'ip' => 'El :attribute must be a valid IP address.',
    'ipv4' => 'El :attribute must be a valid IPv4 address.',
    'ipv6' => 'El :attribute must be a valid IPv6 address.',
    'json' => 'El :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'El :attribute must be less than :value.',
        'file' => 'El :attribute must be less than :value kilobytes.',
        'string' => 'El :attribute must be less than :value characters.',
        'array' => 'El :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'El :attribute must be less than or equal :value.',
        'file' => 'El :attribute must be less than or equal :value kilobytes.',
        'string' => 'El :attribute must be less than or equal :value characters.',
        'array' => 'El :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'El :attribute puede no ser mayor que :max.',
        'file' => 'El :attribute puede no ser mayor que :max kilobytes.',
        'string' => 'El :attribute may not be greater than :max characters.',
        'array' => 'El :attribute may not have more than :max items.',
    ],
    'mimes' => 'El :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'El :attribute debe ser como mínimo :min.',
        'file' => 'El :attribute must be at least :min kilobytes.',
        'string' => 'El :attribute must be at least :min characters.',
        'array' => 'El :attribute must have at least :min items.',
    ],
    'not_in' => 'El seleccionado :attribute es invalido.',
    'not_regex' => 'El :attribute format es invalido.',
    'numeric' => 'El :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'El :attribute field must be present.',
    'regex' => 'El :attribute el formato no es válido.',
    'required' => 'El :attribute se requiere campo.',
    'required_if' => 'El :attribute se requiere campo cuando :other is :value.',
    'required_unless' => 'El :attribute field is required unless :other is in :values.',
    'required_with' => 'El :attribute field is required when :values is present.',
    'required_with_all' => 'El :attribute field is required when :values are present.',
    'required_without' => 'El :attribute field is required when :values is not present.',
    'required_without_all' => 'El :attribute field is required when none of :values are present.',
    'same' => 'El :attribute and :other must match.',
    'size' => [
        'numeric' => 'El :attribute must be :size.',
        'file' => 'El :attribute must be :size kilobytes.',
        'string' => 'El :attribute must be :size characters.',
        'array' => 'El :attribute must contain :size items.',
    ],
    'starts_with' => 'El :attribute must start with one of the following: :values.',
    'string' => 'El :attribute debe ser una cuerda.',
    'timezone' => 'El :attribute must be a valid zone.',
    'unique' => 'El :attribute ya se ha tomado.',
    'uploaded' => 'El :attribute failed to upload.',
    'url' => 'El :attribute format es invalido.',
    'uuid' => 'El :attribute must be a valid UUID.',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
