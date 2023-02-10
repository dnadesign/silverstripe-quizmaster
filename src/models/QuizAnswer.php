<?php

namespace DNADesign\QuizMaster\Models;

use SilverStripe\ORM\DataObject;

class QuizAnswer extends DataObject
{
    private static $table_name = 'DNADesign_QuizAnswer';

    private static $singular_name = 'Answer';

    private static $plural_name = 'Answers';

    private static $has_one = [
        'ParentQuestion' => QuizQuestionStep::class
    ];
}
