<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Interfaces\QuizQuestion;
use DNADesign\QuizMaster\Interfaces\QuizResultStep;
use DNADesign\QuizMaster\Models\QuizContentStep;
use DNADesign\QuizMaster\Traits\QuizPermissionsTrait;
use SilverStripe\Control\Controller;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class Quiz extends DataObject
{
    use Configurable;
    use QuizPermissionsTrait;
    
    private static $table_name = 'DNADesign_Quiz';

    private static $singular_name = 'Quiz';

    private static $plural_name = 'Quizzes';

    /**
     * @config
     */
    private static $use_module_css = true;

    /**
     * @config
     */
    private static $use_module_js = true;

    /**
     * @config
     */
    private static $allowed_step_classes = [];

    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    private static $has_many = [
        'Steps' => QuizStep::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'TItle' => 'Title',
        'Steps.Count' => '# steps'
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            if ($this->IsInDB()) {
                $steps = $fields->dataFieldByName('Steps');
                if ($steps) {
                    // Move steps to main tab
                    $fields->removeByName('Steps');
                    $fields->addFieldToTab('Root.Main', $steps);
                    // Change step selector
                    $config = $steps->getConfig();
                    if ($config) {
                        $addNew = GridFieldAddNewMultiClass::create()
                        ->setClasses($this->getAllowedStepClasses());
                        
                        $config->removeComponentsByType(GridFieldAddNewButton::class);
                        $config->addComponent($addNew);

                        $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);

                        $delete = $config->getComponentByType(GridFieldDeleteAction::class);
                        if ($delete) {
                            $delete->setRemoveRelation(false); // delete instead of unlink
                        }
                    }
                    // Allow to re-order
                    $config->addComponent(new GridFieldOrderableRows('Sort'));
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
    public function getAllowedStepClasses()
    {
        $classes = [];

        $allowed = $this->config()->get('allowed_step_classes');
        if (empty($allowed) || !is_array($allowed)) {
            $allowed = ClassInfo::subclassesFor(QuizStep::class, false);
        }

        if (!empty($allowed)) {
            foreach ($allowed as $class) {
                $classes[$class] = $class::singleton()->config()->get('singular_name');
            }
        }
            
        return $classes;
    }

    /**
     * Compute the number of a question step
     * assuming that we exclude any step that is not a question
     *
     * @return int
     */
    public function getQuestionNumberForStep($id)
    {
        $questions = $this->getQuestions()->column('ID');
        $index = array_search($id, $questions);
        
        return $index !== false ? $index + 1 : 0;
    }

    /**
     * Return all steps that extends QuizQuestion
     *
     * @return DataList
     */
    public function getQuestions()
    {
        return $this->Steps()->filterByCallback(function ($item) {
            return $item instanceof QuizQuestion;
        });
    }

    /**
     *
     */
    public function getStepType($id)
    {
        $step = $this->Steps()->byID($id);
        $type = 'step';

        if ($step) {
            if (ClassInfo::classImplements($step->ClassName, QuizQuestion::class)) {
                $type = 'question';
            } elseif (ClassInfo::classImplements($step->ClassName, QuizResultStep::class)) {
                $type = 'result';
            } elseif ($step instanceof QuizContentStep) {
                $type = 'content';
            }
        }

        $this->extend('updateStepType', $type, $step, $id);

        return $type;
    }

    /**
     * Generate the link to the submit action
     *
     * @return string
     */
    public function getFormActionURL()
    {
        return Controller::join_links(Controller::curr()->Link('submitquiz'), $this->ID);
    }

    /**
     * Find all the questions that define UseValueAsScore
     * and calculate the sum of their submitted values
     *
     * @return int|false
     */
    public function computeScore($data)
    {
        // Find score-able questions
        $questions = $this->getQuestions()->filterByCallback(function ($item) {
            return $item->hasField('UseValueAsScore') && (boolean) $item->UseValueAsScore === true;
        });

        if ($questions->count() === 0) {
            return false;
        }

        // Find questions that have been submitted
        $scoreData = [];
        $fieldNames = $questions->column('FieldName');

        foreach ($data as $fieldName => $value) {
            if (!in_array($fieldName, $fieldNames)) {
                continue;
            }

            // If multiple values can be submitted, add them up
            if (is_array($value)) {
                $scoreData[$fieldName] = array_sum($value);
            } else {
                $scoreData[$fieldName] = $value;
            }
        }

        // Compute score
        if (!empty($scoreData)) {
            return array_sum($scoreData);
        }

        return false;
    }

    public function getZeroBaseStepCount()
    {
        $count = (int) $this->Steps()->count();
        return $count > 0 ? $count - 1 : $count;
    }

    public function forTemplate(): string
    {
        return $this->renderWith(static::class);
    }
}
