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
    'accepted'             => ':attribute must be accepted.',
    'active_url'           => ':attribute không phải là một url hợp lệ.',
    'after'                => ':attribute phải là kiểu ngày tháng sau :date.',
    'after_or_equal'       => ':attribute phải là kiểu ngày tháng sau hoặc bằng :date.',
    'alpha'                => ':attribute chỉ được bao gồm các chữ cái.',
    'alpha_dash'           => ':attribute chỉ được bao gồm các chữ số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num'            => ':attribute chỉ được bao gồm các chữ số.',
    'array'                => ':attribute phải là một mảng.',
    'before'               => ':attribute phải là kiểu ngày tháng trước :date.',
    'before_or_equal'      => ':attribute phải là kiểu ngày tháng trước hoặc bằng :date.',
    'between'              => [
        'numeric' => ':attribute phải là số nguyên từ :min đến :max.',
        'file'    => ':attribute phải có dung lượng từ :min đến :max kilobytes.',
        'string'  => ':attribute phải từ :min đến :max ký tự.',
        'array'   => ':attribute phải từ :min đến :max phần tử.',
    ],
    'boolean'              => ':attribute phải có giá trị true hoặc false.',
    'confirmed'            => 'attribute không khớp.',
    'date'                 => ':attribute không đúng định dạng.',
    'date_format'          => ':attribute phải đúng định dạng :format.',
    'different'            => ':attribute and :other phải khác nhau.',
    'digits'               => ':attribute phải là kiểu số :digits ký tự.',
    'digits_between'       => ':attribute phải là kiểu số, từ :min đến :max ký tự.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => ':attribute có một giá trị trùng lặp.',
    'email'                => ':attribute phải đúng định dạng email.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => ':attribute phải là một file.',
    'filled'               => 'The :attribute field must have a value.',
    'gt'                   => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file'    => ':attribute phải lớn hơn :value kilobytes.',
        'string'  => ':attribute phải lớn hơn :value ký tự.',
        'array'   => ':attribute phải có nhiều hơn :value phần tử.',
    ],
    'gte'                  => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'file'    => ':attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string'  => ':attribute phải lớn hơn hoặc bằng :value ký tự.',
        'array'   => ':attribute phải có :value phần tử hoặc nhiều hơn.',
    ],
    'image'                => ':attribute phải là một ảnh.',
    'in'                   => ':attribute không hợp lệ.',
    'in_array'             => 'Trường :attribute không tồn tại trong :other.',
    'integer'              => ':attribute phải là số nguyên.',
    'ip'                   => ':attribute phải đúng định dạng địa chỉ IP.',
    'ipv4'                 => ':attribute phải đúng định dạng địa chỉ IPv4.',
    'ipv6'                 => ':attribute phải đúng định dạng địa chỉ IPv6.',
    'json'                 => ':attribute phải đúng định dạng JSON.',
    'lt'                   => [
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'file'    => ':attribute phải nhỏ hơn :value kilobytes.',
        'string'  => ':attribute phải nhỏ hơn :value ký tự.',
        'array'   => ':attribute phải nhỏ hơn :value phần tử.',
    ],
    'lte'                  => [
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'file'    => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string'  => ':attribute phải nhỏ hơn hoặc bằng :value ký tự.',
        'array'   => ':attribute không được nhiều hơn :value phần tử.',
    ],
    'max'                  => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max kilobytes.',
        'string'  => ':attribute không được nhiều hơn :max ký tự.',
        'array'   => ':attribute không được nhiều hơn :max phần tử.',
    ],
    'mimes'                => ':attribute phải có kiểu file là: :values.',
    'mimetypes'            => ':attribute phải là một trong các kiểu file: :values.',
    'min'                  => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :min.',
        'file'    => ':attribute phải lớn hơn hoặc bằng :min kilobytes.',
        'string'  => ':attribute phải nhiều hơn hoặc bằng :min ký tự.',
        'array'   => ':attribute phải có ít nhất :min phần tử.',
    ],
    'not_in'               => ':attribute không hợp lệ.',
    'not_regex'            => ':attribute không đúng định dạng.',
    'numeric'              => ':attribute phải là kiểu số.',
    'present'              => 'Trường :attribute không tồn tại.',
    'regex'                => ':attribute không đúng định dạng.',
    'required'             => ':attribute không được để trống.',
    'required_if'          => ':attribute không được để trống nếu :other có giá trị là :value.',
    'required_unless'      => ':attribute không được để trống trừ khi :other có giá trị là :values.',
    'required_with'        => ':attribute không được để trống khi tồn tại một trong các các trường :values.',
    'required_with_all'    => ':attribute không được để trống khi tồn tại các trường :values.',
    'required_without'     => ':attribute không được để trống khi không tồn tại một trong các trường :values.',
    'required_without_all' => ':attribute không được để trống khi tất cả các trường :values không tồn tại.',
    'same'                 => ':attribute và :other phải khớp nhau.',
    'size'                 => [
        'numeric' => ':attribute phải là :size.',
        'file'    => ':attribute phải có dung lượng :size kilobytes.',
        'string'  => ':attribute chỉ được bao gồm :size ký tự.',
        'array'   => ':attribute chỉ được bao gồm :size phần tử.',
    ],
    'string'        => ':attribute phải đúng định dạng string.',
    'timezone'      => 'Timezone của :attribute không hợp lệ.',
    'unique'        => ':attribute đã tồn tại.',
    'uploaded'      => 'Upload :attribute lỗi.',
    'url'           => ':attribute phải đúng định dạng url.',
    'uuid'          => ':attribute phải đúng định dạng uuid.',
    'datetime'      => ':attribute không đúng định dạng.',
    'templatecode'  => ':attribute không đúng định dạng.',
    'invoiceseries' => ':attribute không đúng định dạng.',
    'emails'        => ':attribute phải đúng định dạng email.',
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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [],
];