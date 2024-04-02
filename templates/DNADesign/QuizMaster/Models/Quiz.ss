<div class="quiz" data-quiz data-current-step="1">
    <div class="quiz-progress">
        <button type="button" data-back>Back</button>
        <% include QuizProgressBar %>
    </div>
    <form id="$FormTitle" class="quiz-form" action="$FormActionURL" autocompelete="false" method="post">
        <% loop $Steps %>
        <div class="quiz-step<% if $IsFirst %> active<% end_if %>" 
            aria-hidden="<% if $IsFirst %>false<% else %>true<% end_if %>" 
            data-step="$Pos" 
            data-step-type="$Up.getStepType($ID)"
            data-step-id="$ID">
                $Me
            </div>
        <% end_loop %>
    </form>
</div>