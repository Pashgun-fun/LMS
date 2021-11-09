<style>
    .news__read {
        width: 100px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        color: black;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 15px;
        background: red;
    }

    .news__read:hover {
        cursor: pointer;
    }
</style>

<div class="news__wrapper _container">
    <div class="news__title"><?= $title ?></div>
    <span class="id__news" style="opacity: 0"><?=$id?></span>
    <div class="news__text"><?= $text ?></div>
    <div class="news__read">Читать</div>
    <div>
        <span class="news__user"><?= $user ?></span>
        <div>
            <div class="newsFull__delete">Удалить</div>
            <div class="newsFull__edit">Редактировать</div>

        </div>
        <span><?= $date ?></span>
    </div>
</div>