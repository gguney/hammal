<?php
namespace App\Http\DataModels;

use Hammal\DataModel\DataModel;

class Articles extends DataModel{
	/*
	protected $model = 'Article';
    protected $table = 'articles';
    */
    protected $tableFields = [
        'id','name'
    ];
    protected $formFields = [
        'id','name'
    ];
    protected $hiddenFields = ['id'];
}
?>