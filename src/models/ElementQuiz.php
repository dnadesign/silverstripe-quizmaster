<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\Elemental\Models\BaseElement;

if (!class_exists(BaseElement::class)) {
    return;
}

class ElementQuiz extends BaseElement
{
    private static $singular_name = 'Quiz';

    private static $plural_name = 'Quizzes';

    private static $description = 'Display a quiz';

    private static $table_name = 'ElementQuiz';

    private static $icon = 'font-icon-clipboard-pencil';

    private static $inline_editable = true;

    private static $has_one = [
        'Quiz' => Quiz::class
    ];
}