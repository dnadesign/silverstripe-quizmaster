<?php

namespace DNADesign\QuizMaster\Models;

use SilverStripe\ORM\DataObject;

class QuizMultiChoiceOption extends DataObject
{
    private static $table_name = 'DNADesign_QuizMultiChoiceOption';

    private static $singular_name = 'Option';

    private static $plural_name = 'Options';

    private static $db = [
        'Label' => 'Varchar(255)',
        'Value' => 'Varchar(255)',
        'IsCorrectAnswer' => 'Boolean'
    ];

    private static $has_one = [
        'ParentQuestion' => QuizMultiChoiceQuestionStep::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Label' => 'Label',
        'Value' => 'value',
        'IsCorrectAnswer.Nice' => 'Correct answer'
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
        $isScore = $this->ParentQuestion() ? (boolean) $this->ParentQuestion()->UseValueAsScore : false;
        if ($value) {
            $value->setDescription('What is recorded by the system.');
            if ($isScore) {
                $value->setInputType('number');
            }
        }

        return $fields;
    }

    public function getTitle()
    {
        return $this->Label;
    }
}
