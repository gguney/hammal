<?php
namespace GGuney\Hammal\Ruler;

use GGuney\Hammal\Contracts\RulerContract;

class Ruler implements RulerContract
{

    /**
     * Type conversation for validator.
     *
     * @var array
     */
    protected static $VALIDATOR_TYPES = ['text'           => 'string',
                                         'number'         => 'numeric',
                                         'email'          => 'email',
                                         'password'       => 'string',
                                         'date'           => 'date',
                                         'datetime-local' => 'date',
                                         'checkbox'       => 'string',
                                         'time'           => 'time',
                                         'file'           => 'file',
        'boolean' => 'boolean'
    ];

    /**
     * Validation seperator.
     *
     * @var string
     */
    protected static $OR = '|';

    public static function getRules($columns, $formFields)
    {
        $rules = [];
        foreach ($columns as $column) {
            if (in_array($column->getName(), $formFields)) {
                $rule = null;
                $rule = ($column->getRequired() == 'required' && $column->getEditable()) ? "required" : "nullable";
                $rule .= self::$OR . self::$VALIDATOR_TYPES[$column->getType()];
                $rule .= self::$OR . "max:" . $column->getMaxlength();
                $rules[$column->getName()] = $rule;
            }
        }
        return $rules;
    }

}