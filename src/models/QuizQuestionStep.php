<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Models\QuizAnswer;
use DNADesign\QuizMaster\Models\QuizStep;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

class QuizQuestionStep extends QuizStep
{
    private static $table_name = 'DNADesign_QuizQuestionStep';

    private static $singular_name = 'Question';

    private static $plural_name = 'Questions';

    private static $step_is_question = true;

    private static $db = [
        'Question' => 'Varchar(255)',
        'CanSkip' => 'Boolean'
    ];

    private static $has_many = [
        'Answers' => QuizAnswer::class
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            // Move answers
            if ($this->IsInDB()) {
                $answers = $fields->dataFieldByName('Answers');
                if ($answers) {
                    // Remove tab
                    $fields->removeByName('Answers');
                    // Alter config
                    $config = $answers->getConfig();
                    if ($config) {
                        $addNew = GridFieldAddNewMultiClass::create()
                        ->setClasses($this->getAllowedAnswerClasses());
                        
                        $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                        $config->removeComponentsByType(GridFieldAddNewButton::class);
                        $config->addComponent($addNew);
                    }

                    $fields->addFieldTotab('Root.Main', $answers);
                }
            }
        });

        return parent::getCMSFields();
    }

    /**
     * Computes the list of allowed classes to be used
     * for the Add New button
     * This is mainly because QuizStep should not be used as is
     * but DataObject cannot be abstract classes
     *
     * @return array
     */
    public function getAllowedAnswerClasses()
    {
        $classes = [];

        $allowed = $this->config()->get('allowed_answer_classes');
        if (empty($allowed) || !is_array($allowed)) {
            $allowed = ClassInfo::subclassesFor(QuizAnswer::class, false);
        }

        if (!empty($allowed)) {
            foreach ($allowed as $key => $class) {
                $classes[$class] = $class::singleton()->config()->get('singular_name');
            }
        }
            
        return $classes;
    }
}
