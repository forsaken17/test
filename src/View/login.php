<form action="/login" id="editForm" method="post">
    <input type="hidden" name="token" value="<?= $token ?>">
    <label for="email">Email</label>
    <input type="email" name="email" placeholder="email" value=""><br/>
    <label for="password">Password</label>
    <input type="password" name="password" value=""><br/>
    <input type="submit" value="login">
</form>
<div class="orange"><?= showMessage('error') ?></div>
<div class="register"><a href="/register/">sign up</a></div>