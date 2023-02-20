<?php

namespace DNADesign\QuizMaster\Shortcodes;

use DNADesign\QuizMaster\Models\Quiz;
use SilverStripe\View\Parsers\ShortcodeHandler;

class QuizShortcodeProvider implements ShortcodeHandler
{
    /**
     * Gets the list of shortcodes provided by this handler
     *
     * @return mixed
     */
    public static function get_shortcodes()
    {
        return ['quiz'];
    }

    public static function handle_shortcode($arguments, $content, $parser, $shortcode, $extra = [])
    {
        if (!isset($arguments['id'])) {
            return $content;
        }

        $quiz = Quiz::get()->byID($arguments['id']);
        if (!$quiz || !$quiz->exists()) {
            return $content;
        }

        return $quiz->forTemplate();
    }
}
