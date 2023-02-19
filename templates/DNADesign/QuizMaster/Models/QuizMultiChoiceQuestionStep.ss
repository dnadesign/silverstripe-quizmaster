<div class="quiz-step-question">
    <fieldset>
        <legend class="quiz-step__legend" for="question-{$ID}">
            <div class="quiz-step__question-number">$ParentQuiz.getQuestionNumberForStep($ID)</div>
            <div class="quiz-step__question">$Question</div>
        </legend>
        <div class="quiz-step__field">
            $FormField
        </div>
    </fieldset>
    <div class="quiz-step__actions">
        <button type="button" data-next><% _t("DNADesign\\QuizMaster\\Models\\Quiz.CONTINUE", 'Continue') %></button>
    </div>
</div>