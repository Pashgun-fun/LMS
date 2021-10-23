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

<div class="article__wrapper news__wrapper _container">
    <div><?= $title ?></div>
    <div><?= $text ?></div>
    <div class="news__read">Читать</div>
    <div>
        <span><?= $user ?></span>
        <span><?= $date ?></span>
    </div>
</div>