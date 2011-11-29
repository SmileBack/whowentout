<?php

class ModelCollection
{

    /**
     * @var \DatabaseTable
     */
    protected $table;
    protected $models = array();

    protected $model_name;

    function __construct(DatabaseTable $table, $model_name)
    {
        $this->table = $table;
        $this->model_name = $model_name;
    }

    /**
     * @param  $attributes
     * @return Model
     */
    function create($attributes)
    {
        $row = $this->table->create_row($attributes);
        $model = $this->create_from_row($row);
        $this->models[$model->id] = $model;
        return $model;
    }

    function find($id)
    {
        if (!isset($this->models[$id])) {
            $row = $this->table->row($id);
            $this->models[$id] = $row ? $this->create_from_row($row) : null;
        }
        
        return $this->models[$id];
    }

    function destroy($id)
    {
        $this->table->destroy_row($id);
        
        if (isset($this->models[$id]))
            unset($this->models[$id]);
    }

    private function create_from_row(DatabaseRow $row)
    {
        $model = f()->class_loader()->init_subclass('Model', $this->model_name, $row);
        return $model;
    }

//    /**
//     * @return int
//     */
//    function count()
//    {
//    }
//
//    /**
//     * @return Model
//     */
//    function first()
//    {
//    }
//
//    /**
//     * @return ModelCollection
//     */
//    function limit()
//    {
//    }
//
//    function contains()
//    {
//    }
//
//    /**
//     * @return ModelCollection
//     */
//    function order()
//    {
//    }
//
//    function destroy()
//    {
//    }
//
//    function add()
//    {
//    }

}
