<div class="deal">
    <div class="parent" data-id="<?php echo $data->id; ?>">
        <div class="message">
            Пользователь с электронной почтой "<?php echo $data->Parent->email; ?>" отправил запрос на подключение к вашему аккаунту.
Подтвердите запрос только если вы узнаете адрес эл.почты и полностью уверены, что это один из ваших родителей. В дальнейшем он сможет просматривать все ваши действия в системе и выдавать вам задания.
        </div>
        <div class="confirmation">
            <a href="#" class="btn btn-success btn-sm yes">Подтвердить</a>
            <a href="#" class="btn btn-danger btn-sm no">Отклонить</a>
        </div>
    </div>
</div>