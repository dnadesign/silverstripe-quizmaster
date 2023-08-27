<?php

namespace DNADesign\QuizMaster\Admins;

use DNADesign\QuizMaster\Models\Quiz;
use SilverStripe\Admin\ModelAdmin;

class QuizAdmin extends ModelAdmin
{
    private static $url_segment = 'quizzes';

    private static $managed_models = [
        Quiz::class
    ];

    private static $menu_title = 'Quiz';

    private static $menu_icon_class = 'font-icon-clipboard-pencil';

    private static $required_permission_codes = 'CMS_ACCESS_QUIZMASTER';
}
