<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Interfaces\QuizQuestion;
use DNADesign\QuizMaster\Models\QuizStep;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;

class QuizMultiChoiceQuestionStep extends QuizStep implements QuizQuestion
{
    private static $table_name = 'DNADesign_QuizQuestionStep';

    private static $singular_name = 'Multi-choice question';

    private static $plural_name = 'Multi-choice questions';

    private static $db = [
        'Question' => 'Varchar(255)',
        'Type' => 'Enum("OptionSet, Select")',
        'CanSelectMultiple' => 'Boolean',
        'UseValueAsScore' => 'Boolean',
        'IsRequired' => 'Boolean'
    ];

    private static $has_many = [
        'Options' => QuizMultiChoiceOption::class
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            // Move options
            if ($this->IsInDB()) {
                $options = $fields->dataFieldByName('Options');
                if ($options) {
                    // Remove tab
                    $fields->removeByName('Options');
                    // Alter config
                    $config = $options->getConfig();
                    if ($config) {
                        $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                    }

                    $fields->addFieldTotab('Root.Main', $options);
                }
            }
        });

        return parent::getCMSFields();
    }
}
