<form method="post" action="<?= url()->path('/fitness/auth/login') ?>">
    <table class="table">
        <tr>
            <th>
                <label for="login">Логин</label>
            </th>
            <td>
                <input id="login" type="text" name="login" value="" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="password">Пароль</label>
            </th>
            <td>
                <input id="password" type="password" name="password" value="" />
            </td>
        </tr>
        <tr>
            <th>
            </th>
            <td>
                <button type="submit">Войти</button>
            </td>
        </tr>
    </table>
</form>
<? if (!empty($error)) : ?>
    <p class="text-danger"><?= $error ?></p>
<? endif; ?>
