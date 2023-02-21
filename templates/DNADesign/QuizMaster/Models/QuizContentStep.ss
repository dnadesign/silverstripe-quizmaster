<div class="quiz-step-content">
    <% if $Image %>
        <div class="quiz-step__image" style="background-image:url('{$Image.URL}')"></div>
    <% end_if %>
    <div class="quiz-step__main">
        <div class="quiz-step__body">
            <div class="quiz-step__content">$Content</div>
        </div>
        <div class="quiz-step__actions">
            <button type="button" data-next><% if $ButtonCustomLabel %>$ButtonCustomLabel<% else %><% _t("DNADesign\\QuizMaster\\Models\\Quiz.CONTINUE", 'Continue') %><% end_if %></button>
        </div>
    </div>
</div>