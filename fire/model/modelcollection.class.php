<?php

class ModelCollection
{

    /**
     * @var \DatabaseTable
     */
    protected $table;
    protected $models = array();

    protected $model_name;

    function __construct(DatabaseTable $table)
    {
        $this->table = $table;
        $this->model_name = Inflect::singularize($table->name());
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
        $model_class = $this->model_name;
        return new $model_class($row);
    }

    /**
     * @return int
     */
    function count()
    {
        return $this->table->count();
    }
}
