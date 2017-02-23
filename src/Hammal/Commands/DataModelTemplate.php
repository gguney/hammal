<?php
namespace App\Http\DataModels;

use Hammal\DataModel\DataModel;

class {DM_NAME} extends DataModel
{
    /**
     * Model name for the related model. Can be deleted.
     * 
     * @var string
     */
    protected $model = '{MODEL_NAME}';

    /**
     * Table name for the related model. Can be deleted.
     * 
     * @var string
     */
    protected $table = '{TABLE_NAME}';

    /**
     * Fields array to render in table partial view. Can be deleted if all fields will be visible.
     * 
     * @var array
     */
    protected $tableFields = [
        'id'
    ];

    /**
     * Fields array to render in form partial view. Can be deleted if all fields will be visible.
     * 
     * @var array
     */
    protected $formFields = [
        'id'
    ];

    /**
     * Fields array to be hidden in table and form partial view. Can be deleted if there is no hidden field. Can be deleted if Model's hidden array is the same.
     * 
     * @var array
     */
    protected $hiddenFields = [

    ];
}