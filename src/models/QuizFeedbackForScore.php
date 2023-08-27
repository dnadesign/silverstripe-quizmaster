<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Traits\QuizPermissionsTrait;
use SilverStripe\ORM\DataObject;

class QuizFeedbackForScore extends DataObject
{
    use QuizPermissionsTrait;

    private static $table_name = 'DNADesign_QuizFeedbackForScore';

    private static $singular_name = 'Feedback';

    private static $plural_name = 'Feedbacks';

    private static $db = [
        'MinScore' => 'Int',
        'MaxScore' => 'Int',
        'Feedback' => 'HTMLText'
    ];

    private static $has_one = [
        'ParentStep' => QuizScoreResultStep::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'MinScore' => 'Min',
        'MaxScore' => 'Max',
        'Feedback.Summary' => 'Feedback'
    ];
}
