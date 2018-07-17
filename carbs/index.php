<?php  ?>
<form id="login" target="frame" method="post" action="https://app.carbmanager.com/account/signin">
    <input type="hidden" name="username" value="rbrinker77@yahoo.com" />
    <input type="hidden" name="password" value="vB6we2%LWyJWww9klWHu" />
</form>

<iframe id="frame" name="frame"></iframe>

<script type="text/javascript">
    // submit the form into iframe for login into remote site
    document.getElementById('login').submit();

    // once you're logged in, change the source url (if needed)
    var iframe = document.getElementById('frame');
    iframe.onload = function() {
        if (iframe.src != "https://app.carbmanager.com/account/signin") {
            iframe.src = "https://app.carbmanager.com/account/signin";
        }
    }
</script>
