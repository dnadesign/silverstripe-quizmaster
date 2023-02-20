<div class="quiz-step-result">
    <% if $Feedbacks %>
        <div class="quiz-step__body">
        <% loop $Feedbacks %>
            $Feedback
        <% end_loop %>
        </div>
        <div class="quiz-step__actions">
            <button type="reset" data-reset><% _t('DNADesign\QuizMaster\Quiz.RESTART', 'Restart the quiz') %></button>
        </div>
    <% end_if %>
</div>