<style>
    .articleFull__wrapper {
        position: fixed;
        width: 100%;
        height: 100vh;
        background: white;
        bottom: 0;
        left: 0;
        z-index: 200;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .articleFull__title {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .articleFull__text {
        text-align: justify;
        font-size: 1.5rem;
        line-height: 40px;
        margin-bottom: 15px;
    }

    .articleFull__info {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .articleFull__name {
        font-size: 1.3rem;
    }

    .articleFull__date {
        font-size: 1.1rem;
        color: #afaeae;
        float: right;
    }

    .articleFull {
        width: 50%;
    }

    .articleFull__close {
        width: 50px;
        height: 50px;
        background: black;
        position: absolute;
        top: 30px;
        right: 30px;
    }

    .articleFull__close:hover {
        cursor: pointer;
    }
</style>

<div class="articleFull__wrapper _container">
    <div class="articleFull__close"></div>
    <div class="articleFull">
        <div class="articleFull__title"><?= $title ?></div>
        <div class="articleFull__text"><?= $text ?></div>
        <div class="articleFull__info">
            <span class="articleFull__name"><?= $user ?></span>
            <span class="articleFull__date"><?= $date ?></span>
        </div>
    </div>
</div>