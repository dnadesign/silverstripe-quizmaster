<?php

namespace DNADesign\QuizMaster\Models;

use DNADesign\QuizMaster\Interfaces\QuizResultStep;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;

class QuizScoreResultStep extends QuizStep implements QuizResultStep
{
    private static $table_name = 'DNADesign_QuizScoreResultStep';

    private static $singular_name = 'Score Result';

    private static $plural_name = 'Score results';

    private static $has_many = [
        'FeedbackForScores' => QuizFeedbackForScore::class
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            if ($this->IsInDB()) {
                $feedback = $fields->dataFieldByName('FeedbackForScores');
                if ($feedback) {
                    $config = $feedback->getConfig();
                    if ($config) {
                        $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                    }
                    $fields->removeByName('FeedbackForScores');
                    $fields->addFieldToTab('Root.Main', $feedback);
                }
            }
        });

        return parent::getCMSFields();
    }

    /**
     * Renders the template with the populated feedback
     *
     * @return DBHTMLText
     */
    public function getFeedback($data)
    {
        $quiz = $this->ParentQuiz();
        if (!$quiz) {
            user_error('Trying to get feedback from a step that doesn\'t belong to a quiz');
        }

        $score = (int) $quiz->computeScore($data);
        if ($score === false) {
            return _t(Quiz::class.'.SOMETHINGWRONG', 'Sorry something went wrong, please try again.');
        }

        $feedbacks = $this->FeedbackForScores()
            ->filter(['MinScore:LessThanOrEqual' => $score, 'MaxScore:GreaterThanOrEqual' => $score]);

        $result = $this->customise(['Feedbacks' => $feedbacks])->forTemplate();

        $this->extend('updateGetFeedback', $data, $result);

        return $result;
    }
}
