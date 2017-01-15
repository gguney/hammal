<?php
namespace ModelCourier\ColumnHelper;

class Column
{
    protected $specialType;
    protected $editable;
    protected $default;
    protected $attributes = array();


    /**
     * Gets the value of label.
     *
     * @return mixed
     */
    public function set($index, $value)
    {
        $this->attributes[$index] = $value;
    }

    public function get($index)
    {
        return (isset($this->attributes[$index]))?$this->attributes[$index]:null;

    }

    /**
     * Gets the value of atrributes.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the value of atrributes.
     *
     * @param mixed $atrributes the atrributes
     *
     * @return self
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the value of specialType.
     *
     * @return mixed
     */
    public function getSpecialType()
    {
        return $this->specialType;
    }

    /**
     * Sets the value of specialType.
     *
     * @param mixed $specialType the special type
     *
     * @return self
     */
    public function setSpecialType($specialType)
    {
        $this->specialType = $specialType;

        return $this;
    }

    /**
     * Gets the value of editable.
     *
     * @return mixed
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Sets the value of editable.
     *
     * @param mixed $editable the editable
     *
     * @return self
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Gets the value of default.
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets the value of default.
     *
     * @param mixed $default the default
     *
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}

?>				