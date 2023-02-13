<?php

namespace DNADesign\QuizMaster\Extensions;

use DNADesign\QuizMaster\Interfaces\QuizResultStep;
use DNADesign\QuizMaster\Models\Quiz;
use DNADesign\QuizMaster\Models\QuizStep;
use SilverStripe\Core\Extension;

class PageControllerQuizExtension extends Extension
{
    private static $allowed_actions = ['submitquiz'];

    public function submitquiz()
    {
        $quizId = $this->owner->getRequest()->param('ID');
        if (!$quizId || !is_numeric($quizId)) {
            return $this->owner->httpError(403);
        }

        $quiz = Quiz::get()->byID($quizId);
        if (!$quiz || !$quiz->exists()) {
            return $this->owner->httpError(404);
        }

        $data = $this->owner->getRequest()->postVars();
        $feedback = null;

        if (isset($data['resultStepId']) && is_numeric($data['resultStepId'])) {
            $step = QuizStep::get()->filter(['ID' => $data['resultStepId'], 'ParentQuizID' => $quizId])->first();
            if ($step && $step->exists() && $step instanceof QuizResultStep) {
                $feedback = $step->getFeedback($data);
            }
        }

        if (!$feedback) {
            $score = $quiz->computeScore($data);
            $feedback = _t(Quiz::class.'.FEEDBACK', 'Your score is {score}', ['score' => $score]);
        }

        return $feedback;
    }
}
