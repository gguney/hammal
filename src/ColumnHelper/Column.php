<?php
namespace GGuney\Hammal\ColumnHelper;

use GGuney\Hammal\Contracts\ColumnContract;

class Column implements ColumnContract
{

    /**
     * Column's editable value.
     *
     * @var string
     */
    protected $editable;

    /**
     * Column's default value.
     *
     * @var string
     */
    protected $default;

    /**
     * Attributes of the column.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Set value to given index.
     *
     * @param string $index
     * @param string $value
     *
     * @return void
     */
    public function set($index, $value)
    {
        $this->attributes[$index] = $value;
    }

    /**
     * Get value for the given index.
     *
     * @param string $index
     *
     * @return string
     */
    public function get($index)
    {
        return (isset($this->attributes[$index])) ? $this->attributes[$index] : null;

    }

    /**
     * Gets the value of atrributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the value of atrributes.
     *
     * @param array $atrributes
     *
     * @return Column
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get the editable value of column.
     *
     * @return string
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set the value of editable.
     *
     * @param string $editable
     *
     * @return Column
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get the default value of column.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default value for column.
     *
     * @param string $default
     *
     * @return Column
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    public function __call($method, $args)
    {
        if (starts_with($method, 'set')) {
            $index = camel_case(substr($method, 3));//strlen('set')
            if (isset($args[0])) {
                $this->set($index, $args[0]);
            }
        } else if (starts_with($method, 'get')) {
            $index = camel_case(substr($method, 3));//strlen('get')
            return $this->get($index);
        }
    }
}

?>				