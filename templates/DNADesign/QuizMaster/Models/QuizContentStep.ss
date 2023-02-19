<div class="quiz-step-content">
    <div class="quiz-step__body">
        <div class="quiz-step__content">$Content</div>
        <% if $Image %>
        <div class="quiz-step__Image">$Image</div>
        <% end_if %>
    </div>
    <div class="quiz-step__actions">
        <button type="button" data-next><% if $ButtonCustomLabel %>$ButtonCustomLabel<% else %><% _t("DNADesign\\QuizMaster\\Models\\Quiz.CONTINUE", 'Continue') %><% end_if %></button>
    </div>
</div>