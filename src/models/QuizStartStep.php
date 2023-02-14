<?php

namespace DNADesign\QuizMaster\Models;

use SilverStripe\Assets\Image;

class QuizStartStep extends QuizContentStep
{
    private static $table_name = 'DNADesign_QuizStartStep';

    private static $singular_name = 'Start';

    private static $plural_name = 'Starts';

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $owns = [
        'Image'
    ];
}
