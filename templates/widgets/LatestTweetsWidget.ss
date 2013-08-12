<div class="$ClassName">
    <h4>$Title</h4>
    <div class="tweets">
        <% if Tweets %>
            <% cached %>
                <% loop Tweets %>
                    $Tweet
                    <a class="time" href="$TweetLink">$TweetDate.Ago</a>
                <% end_loop %>
            <% end_cached %>
        <% end_if %>
    </div>
</div>