<?php

namespace Infrastructure\Database\Eloquent;

use Illuminate\Support\Facades\DB;
use Phuongtt\Genie\Repository as BaseRepository;

abstract class Repository extends BaseRepository
{
    public $dirty = [];
    public $originalData;

    public function create(array $data)
    {
        $model = $this->getModel();

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function update($model, array $data)
    {
        $model->fill($data);
        $this->originalData = $model->getOriginal();
        $this->dirty = $model->getDirty();
        $model->save();

        return $model;
    }

    // create not using mass assignment
    public function createNotUsingMassAssignment(array $data)
    {
        $invoice = $this->getModel();

        foreach($data as $key => $value) {
            $invoice->{$key} = $value;
        }
        $invoice->save();
        return $invoice;
    }

    // update not using mass assignment
    public function updateNotUsingMassAssignment($model, array $data)
    {
        foreach($data as $key => $value) {
            $model->{$key} = $value;
        }
        $model->save();

        return $model;
    }

    public function getDataChanged() {
        $dataChange = '';
        if($this->dirty) {
            $dataChange = 'Nội dung thay đổi: ';
            foreach($this->dirty as $key => $value) {
                $this->originalData[$key] = (empty($this->originalData[$key])) ? '' : $this->originalData[$key];
                if ($key == 'json_data') {
                    $json_old = '{"';
                    if ($this->originalData[$key]) {
                        foreach ($this->originalData[$key] as $key1 => $value1) {
                            $json_old .= $key1 . '":"' . $value1 ;
                        }
                    }
                    $json_old .= '"}';
                    $dataChange .= $key . ': ' . $json_old . ' => ' . $value . ', ';
                } else {
                    $dataChange .= $key . ': ' . $this->originalData[$key] . ' => ' . $value . ', ';
                }
            }
        }
        return trim($dataChange, ', ');
    }

	/**
     * Get resources by a where clause
     * @param  string $column
     * @param  mixed $value
     * @param  array $options
     * @return Collection
     */
    public function getWhereWithPagination($column, $value, $tag = 'item', array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->where($column, $value);
        $queryCount = clone $query;
        $total = $queryCount->offset(0)->limit(PHP_INT_MAX)->count();
        $meta = ['total' => $total];
        return [
            'meta' => $meta,
            $tag   =>$query->get(),
        ];
    }

    public function getWhereArrayWithPagination(array $clauses, $tag = 'item', array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->where($clauses);
        $queryCount = clone $query;
        $total = $queryCount->offset(0)->limit(PHP_INT_MAX)->count();
        $meta = ['total' => $total];
        return [
            'meta' => $meta,
            $tag   =>$query->get(),
        ];
    }

    public function getWhereRaw($params, array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->whereRaw($params);

        return $query->get();
    }

}
