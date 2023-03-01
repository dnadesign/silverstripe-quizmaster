<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Interfaces\QuizQuestion;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\OptionsetField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class QuizMultiChoiceQuestionStep extends QuizStep implements QuizQuestion
{
    private static $table_name = 'DNADesign_QuizQuestionStep';

    private static $singular_name = 'Multi-choice question';

    private static $plural_name = 'Multi-choice questions';

    private static $db = [
        'Question' => 'Varchar(255)',
        'FieldName' => 'Varchar(255)',
        'FieldType' => 'Enum("OptionSet, Select")',
        'CanSelectMultiple' => 'Boolean',
        'UseValueAsScore' => 'Boolean',
    ];

    private static $many_many = [
        'Options' => QuizMultiChoiceOption::class
    ];

    private static $many_many_extraFields = [
        'Options' => [
            'Sort' => 'Int'
        ]
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            // Field name
            $fieldName = $fields->dataFieldByName('FieldName');
            if ($fieldName) {
                $fieldName->setReadonly(true);
            }
            // Move options
            if ($this->IsInDB()) {
                $options = $fields->dataFieldByName('Options');
                if ($options) {
                    // Edit config
                    $config = $options->getConfig();
                    if ($config) {
                        $config->addComponent(new GridFieldOrderableRows('Sort'));
                    }
                    // Remove tab
                    $fields->removeByName('Options');
                    $fields->addFieldTotab('Root.Main', $options);
                }
            }
        });

        return parent::getCMSFields();
    }

    /**
     * Generate field name when saving
     */
    public function onAfterWrite()
    {
        parent::onAfterWrite();

        if (!$this->FieldName) {
            $this->FieldName = $this->generateFieldName();
            $this->write();
        }
    }

    /**
     * Return the correct form field
     * to be included in the form
     *
     * @return FormField
     */
    public function getFormField()
    {
        $field = null;
        $source = $this->Options()->sort('Sort ASC')->map('Value', 'Label')->toArray();

        if ($this->FieldType === 'OptionSet') {
            if ($this->CanSelectMultiple) {
                $field = CheckboxSetField::create($this->FieldName, '');
            } else {
                $field = OptionsetField::create($this->FieldName, '');
            }
        } elseif ($this->FieldType === 'Select') {
            $field = DropdownField::create($this->FieldName, '');
            $field->setEmptyString(_t(static::class.'.SELECT', 'Select'));
        }

        $field->setSource($source);

        return $field;
    }

    /**
     * Generate a unique string to be use as reference
     * for the field
     *
     * @return string
     */
    public function generateFieldName()
    {
        return strtolower(sprintf('%s-%s', ClassInfo::shortName(static::class), $this->ID));
    }
}
