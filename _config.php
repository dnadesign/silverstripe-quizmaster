<?php

use DNADesign\QuizMaster\Shortcodes\QuizShortcodeProvider;
use SilverStripe\View\Parsers\ShortcodeParser;

// Initialise Shortcode
ShortcodeParser::get('default')
    ->register('quiz', [QuizShortcodeProvider::class, 'handle_shortcode']);
