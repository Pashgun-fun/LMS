<style>
    .product_container {
        display: grid;
        grid-template-columns: repeat(9, 1fr);
        padding: 20px;
        border: 1px solid silver;
        border-radius: 3px;
        text-align: center;
    }

    .product_container:not(:last-child) {
        margin-bottom: 20px;
    }
</style>
<div>
    <?= $str ?>
    <?php
    if (!empty($arr)) {
        foreach ($arr as $value) {
            echo('<div class="product_container">
                <div class = "product_chapter">' .
                $value['chapter']
                . '</div>
                <div class="product_subchapter">' .
                $value['subchapte']
                . '</div>
               <div class="product_articul">' .
                $value['articul']
                . '</div>
               <div class="product_brend">' .
                $value['brend']
                . '</div>
              <div class="product_model">' .
                $value['model']
                . '</div>
               <div class="product_namespace">' .
                $value['namespace']
                . '</div>
               <div class="product_size">' .
                $value['size']
                . '</div>
               <div class="product_color">' .
                $value['color']
                . '</div>
               <div class="product_orientation">' .
                $value['orientation']
                . '</div>
                </div>');
        }
    }

    ?>
</div>