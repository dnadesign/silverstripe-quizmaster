<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Traits\QuizPermissionsTrait;
use SilverStripe\ORM\DataObject;

class QuizMultiChoiceOption extends DataObject
{
    use QuizPermissionsTrait;

    private static $table_name = 'DNADesign_QuizMultiChoiceOption';

    private static $singular_name = 'Option';

    private static $plural_name = 'Options';

    private static $db = [
        'Label' => 'Varchar(255)',
        'Value' => 'Varchar(255)',
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Label' => 'Label',
        'Value' => 'Value'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // Label
        $label = $fields->dataFieldByName('Label');
        if ($label) {
            $label->setDescription('What the user sees');
        }

        // Value
        $value = $fields->dataFieldByName('Value');
        if ($value) {
            $value->setDescription('What is recorded by the system.');
        }

        return $fields;
    }

    public function getTitle()
    {
        return sprintf('%s (%s)', $this->Label, $this->Value);
    }
}