<div class="quiz-step-result">
    <% if $Feedbacks %>
        <% loop $Feedbacks %>
            $Feedback
        <% end_loop %>
        <button type="reset" data-reset><% _t('DNADesign\QuizMaster\Quiz.RESTART', 'RESTART the quiz') %></button>
    <% end_if %>
</div>