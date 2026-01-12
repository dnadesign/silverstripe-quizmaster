<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Traits\QuizPermissionsTrait;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBText;

class QuizStep extends DataObject
{
    use QuizPermissionsTrait;

    private static $table_name = 'DNADesign_QuizStep';

    private static $singular_name = 'Quiz step';

    private static $plural_name = 'Quiz steps';

    private static $db = [
        'Name' => 'Varchar(255)',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'ParentQuiz' => Quiz::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'getStepType' => 'Type',
        'Name' => 'Name'
    ];

    private static $default_sort = 'Sort ASC';

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName([
                'Sort',
                'ParentQuizID'
            ]);
    
            // Name
            $name = $fields->dataFieldByName('Name');
            if ($name) {
                $name->setDescription('For reference only');
            }
        });

        return parent::getCMSFields();
    }

    /**
     * For gridField summary
     */
    public function getStepType()
    {
        return DBField::create_field(DBText::class, $this->i18n_singular_name());
    }

    public function forTemplate(): string
    {
        return $this->renderWith(static::class);
    }
}
