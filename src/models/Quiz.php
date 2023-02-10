<?php

namespace DNADesign\QuizMaster\Models;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

class Quiz extends DataObject
{
    private static $table_name = 'DNADesign_Quiz';

    private static $singular_name = 'Quiz';

    private static $plural_name = 'Quizzes';

    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    private static $has_many = [
        'Steps' => QuizStep::class
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
            foreach ($allowed as $key => $class) {
                $classes[$class] = $class::singleton()->config()->get('singular_name');
            }
        }
            
        return $classes;
    }
}
