<?php

namespace database;

require_once __DIR__ . "/../core/Autoloader.php";

use database\Migrations\AllThings;
use database\Migrations\Brend;
use database\Migrations\Chapter;
use database\Migrations\Color;
use database\Migrations\ColumnArticles;
use database\Migrations\Model;
use database\Migrations\Subchapter;
use mysqli;
use database\Migrations\Articles;
use database\Migrations\News;
use database\Migrations\Users;
use enums\Connection;
use database\Migrations\Articul;
use database\Migrations\Orientation;
use database\Migrations\Size;

class Rollback
{
    public Articles $articles;
    public News $news;
    public Users $users;
    public mysqli $mysqli;
    public Connection $connection;
    public ColumnArticles $columnArticles;
    public AllThings $allThings;
    public Brend $brend;
    public Model $model;
    public Chapter $chapter;
    public Subchapter $subchapter;
    public Color $color;
    public Articul $articul;
    public Orientation $orientation;
    public Size $size;

    function __construct()
    {
        $this->articles = new Articles();
        $this->news = new News();
        $this->users = new Users();
        $this->columnArticles = new ColumnArticles();
        $this->allThings = new AllThings();
        $this->chapter = new Chapter();
        $this->subchapter = new Subchapter();
        $this->model = new Model();
        $this->color = new Color();
        $this->brend = new Brend();
        $this->articul = new Articul();
        $this->orientation = new Orientation();
        $this->size = new Size();
    }

    /**
     * Метод для отката всех выполненных миграций в обратном порядке их выполнения
     */
    public function rollback()
    {
        $this->connection = Connection::getInstance();

        $this->mysqli = new mysqli(
            $this->connection->getIp(),
            $this->connection->getUsername(),
            $this->connection->getPassword(),
            $this->connection->getDatabase()
        );

        $arrOfMigrations = [];

        $query = "SELECT `caption` FROM homestead.migrations";

        $result = $this->mysqli->query($query);

        while ($migrate = $result->fetch_assoc()) {
            array_push($arrOfMigrations, $migrate);
        }

        if (!empty($arrOfMigrations)) {
            foreach ($arrOfMigrations as $arrOfMigration) {
                switch ($arrOfMigration['caption']) {
                    case "Articles.php":
                        $this->mysqli->query($this->articles->deleteArticleTable());
                        break;
                    case "News.php":
                        $this->mysqli->query($this->news->deleteNewsTable());
                        break;
                    case "Users.php":
                        $this->mysqli->query($this->users->deleteUsersTable());
                        break;
                    case "ColumnArticles.php":
                        $this->mysqli->query($this->columnArticles->deleteColumnArticle());
                        break;
                    case "AllThings.php":
                        $this->mysqli->query($this->allThings->deleteAllThingsTable());
                        break;
                    case "Brend.php":
                        $this->mysqli->query($this->brend->deletBrendTable());
                        break;
                    case "Chapter.php":
                        $this->mysqli->query($this->chapter->deletChapterTable());
                        break;
                    case "Color.php":
                        $this->mysqli->query($this->color->deletColorTable());
                        break;
                    case "Model.php":
                        $this->mysqli->query($this->model->deletModelTable());
                        break;
                    case "Subchapter.php":
                        $this->mysqli->query($this->subchapter->deletSubchapterTable());
                        break;
                    case "Articul.php":
                        $this->mysqli->query($this->articul->deletArticulTable());
                        break;
                    case "Orientation.php":
                        $this->mysqli->query($this->orientation->deletOrientationTable());
                        break;
                    case "Size.php":
                        $this->mysqli->query($this->size->deletSizeTable());
                        break;
                }
            }
        }
    }
}

$rollback = new Rollback();
$rollback->rollback();