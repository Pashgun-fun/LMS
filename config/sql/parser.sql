select homestead.allThings.id,
       homestead.chapter.type    as chapter,
       homestead.subchapter.type as subchapte,
       homestead.allThings.articul,
       homestead.brend.type      as brend,
       homestead.model.type      as model,
       homestead.allThings.namespace,
       homestead.allThings.size,
       homestead.color.type      as color,
       homestead.allThings.orientation
from allThings
         join homestead.chapter on homestead.allThings.`chapter` = homestead.chapter.`id`
         join homestead.subchapter on homestead.allThings.`subchapter` = homestead.subchapter.`id`
         join homestead.brend on homestead.allThings.`brend` = homestead.brend.`id`
         join homestead.model on homestead.allThings.`model` = homestead.model.`id`
         join homestead.color on homestead.allThings.`color` = homestead.color.`id`;