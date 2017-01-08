<?php
namespace ModelCourier\ColumnHelper;

class Column
{
	protected $label;
	protected $name;
	protected $id;
	protected $required;
	protected $editable;
	protected $type;
	protected $maxLength = 255;
	protected $nullable;
	protected $default;
	protected $element;


    /**
     * Gets the value of label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the value of label.
     *
     * @param mixed $label the label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of required.
     *
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Sets the value of required.
     *
     * @param mixed $required the required
     *
     * @return self
     */
    public function setRequired($required)
    {
        $this->required = $required;

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
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of maxLength.
     *
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * Sets the value of maxLength.
     *
     * @param mixed $maxLength the max length
     *
     * @return self
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * Gets the value of nullable.
     *
     * @return mixed
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * Sets the value of nullable.
     *
     * @param mixed $nullable the nullable
     *
     * @return self
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;

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

    /**
     * Gets the value of element.
     *
     * @return mixed
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Sets the value of element.
     *
     * @param mixed $element the element
     *
     * @return self
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }
}

?>				