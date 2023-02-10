<?php

namespace DNADesign\QuizMaster\Models;

use SilverStripe\ORM\DataObject;

class QuizMultiChoiceAnswerOption extends DataObject
{
    private static $table_name = 'DNADesign_QuizMultiChoiceAnswerOption';

    private static $singular_name = 'Option';

    private static $plural_name = 'Options';

    private static $db = [
        'Label' => 'Varchar(255)',
        'Value' => 'Varchar(255)',
        'UseValueAsScore' => 'Boolean',
        'IsCorrectAnswer' => 'Boolean'
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Label' => 'Label',
        'Value' => 'value',
        'UseValueAsScore.Nice' => 'Score',
        'IsCorrectAnswer.Nice' => 'Correct answer'
    ];
}
