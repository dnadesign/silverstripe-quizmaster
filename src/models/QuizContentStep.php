<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Traits\QuizPermissionsTrait;
use SilverStripe\Assets\Image;

class QuizContentStep extends QuizStep
{
    use QuizPermissionsTrait;

    private static $table_name = 'DNADesign_QuizContentStep';

    private static $singular_name = 'Content';

    private static $plural_name = 'Contents';

    private static $db = [
        'Content' => 'HTMLText',
        'ButtonCustomLabel' => 'Varchar(255)'
    ];

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $owns = [
        'Image'
    ];
}