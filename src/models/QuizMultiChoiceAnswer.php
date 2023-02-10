<?php

namespace DNADesign\QuizMaster\Models;

class QuizMultiChoiceAnswer extends QuizAnswer
{
    private static $table_name = 'DNADesign_QuizMultiChoiceAnswer';

    private static $singular_name = 'Multi-choice answer';

    private static $plural_name = 'Multi-choice answers';

    private static $db = [
        'Type' => 'Enum("OptionSet, Select")',
        'CanSelectMultiple' => 'Boolean'
    ];

    private static $has_many = [
        'Options' => QuizMultiChoiceAnswerOption::class
    ];
}
