<style>
    .articles {
        display: -ms-grid;
        display: grid;
        grid-template-columns: repeat(3, 4fr);
        grid-gap: 50px;
        margin: 0 auto 0 auto;
        width: 90%;
    }

    .article__title {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .article__text {
        text-align: justify;
        font-size: 1.1rem;
        line-height: 30px;
        margin-bottom: 15px;
    }

    .article__name {
        font-size: 1.1rem;
    }

    .article__date {
        color: #afaeae;
        float: right;
    }

    .article__read {
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

    .article__read:hover {
        cursor: pointer;
    }
</style>

<div class="article__wrapper _container">
    <div class="article__title"><?= $title ?></div>
    <span class="id__article" style="opacity: 0"><?= $id ?></span>
    <div class="article__text"><?= $text ?></div>
    <div class="article__read">Читать</div>
    <div class="article__info">
        <span class="article__name"><?= $user ?></span>
        <span class="article__date"><?= $date ?></span>
    </div>
</div>