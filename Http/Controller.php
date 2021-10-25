<?php

namespace Infrastructure\Http;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Phuongtt\Bruno\LaravelController;
use Infrastructure\Exceptions as CustomException;

abstract class Controller extends LaravelController
{
	public function validateIds($ids, $attr = 'id') {
		foreach($ids as $value) {
	        $validator = \Validator::make(["id" => $value], [
	            'id' => 'uuid'
	        ], [
	        	'id.uuid' => $attr . ' phải đúng định dạng uuid'
	        ]);
	        if ($validator->fails()) {
	            throw new CustomException\GeneralException("IWE004", 'Dữ liệu đầu vào không hợp lệ: ' . $validator->errors()->first());
	        }
		}
	}
	public function validateId($id, $attr = 'id') {
		$validator = \Validator::make(["id" => $id], [
            'id' => 'uuid'
        ], [
        	'id.uuid' => $attr . ' phải đúng định dạng uuid'
        ]);
        if ($validator->fails()) {
            throw new CustomException\GeneralException("IWE004", 'Dữ liệu đầu vào không hợp lệ: ' . $validator->errors()->first());
        }
	}
}
