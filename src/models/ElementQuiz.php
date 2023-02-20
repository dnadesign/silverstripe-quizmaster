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

    /**
     * THis should not be necessary as $singular is defined
     * Wait for https://github.com/silverstripe/silverstripe-elemental/issues/831
     * to be addressed
     */
    public function getType()
    {
        $default = $this->i18n_singular_name() ?: 'Block';

        return _t(__CLASS__ . '.BlockType', $default);
    }
}