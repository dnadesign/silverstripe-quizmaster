<div class="quiz">
    <form id="$FormTitle" class="quiz-form" action="$FormAction" autocompelete="false" method="post">
        <% loop $Steps %>
            <div data-step="$Pos" data-question="$Up.getQuestionNumberForStep($ID)">
                $Me
            </div>
        <% end_loop %>
    </form>
</div>