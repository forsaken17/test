<form action="/register" id="editForm" method="post">
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="email" name="email" placeholder="email" value="">
    <input type="password" name="password" value="">
    <input type="password" name="password2" value="">
    <input type="submit" value="sign up">
</form>