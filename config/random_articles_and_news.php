<?php
return [
    'articles' => [
        'title' => 'Article',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur assumenda culpa debitis dicta eaque excepturi fugit harum impedit iusto laborum non possimus, quae quis reiciendis saepe temporibus totam ut voluptatem?',
        'user' => 'User',
        'date' => '' . getdate()['mday'] . '.' . getdate()['mon'] . '.' . getdate()['year'],
        'id' => 1
    ],
    'news' => [
        'title' => 'News',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur assumenda culpa debitis dicta eaque excepturi fugit harum impedit iusto laborum non possimus, quae quis reiciendis saepe temporibus totam ut voluptatem?',
        'user' => 'User',
        'date' => '' . getdate()['mday'] . '.' . getdate()['mon'] . '.' . getdate()['year'],
        'seconds' => getdate()[0],
        'id' => 1
    ]
];