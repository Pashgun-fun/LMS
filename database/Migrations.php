<?php

namespace database;

require_once __DIR__ . "/../core/Autoloader.php";

use database\Migrations\AllThings;
use database\Migrations\Brend;
use database\Migrations\Chapter;
use database\Migrations\Color;
use database\Migrations\Model;
use database\Migrations\Subchapter;
use mysqli;
use database\Migrations\Articles;
use database\Migrations\News;
use database\Migrations\Users;
use enums\Connection;
use database\Migrations\ColumnArticles;
use database\Migrations\Articul;
use database\Migrations\Orientation;
use database\Migrations\Size;

class Migrations
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
     * Метод для запуска всех миграций
     */
    public function migrations()
    {

        $arrMigration = array_slice(scandir(__DIR__ . "/Migrations/"), 2);

        $this->connection = Connection::getInstance();

        $this->mysqli = new mysqli(
            $this->connection->getIp(),
            $this->connection->getUsername(),
            $this->connection->getPassword(),
            $this->connection->getDatabase()
        );

        $query = $this->mysqli->query("SELECT `caption` FROM homestead.migrations");

        $arrOfMigrations = [];

        while ($migration = $query->fetch_assoc()) {
            array_push($arrOfMigrations, $migration['caption']);
        }

        /**
         * Выполнится только если массив выполненных миграций пуст
         * т.е. выполнятся все возможные миграции, которые есть в базе
         */
        if (empty($arrOfMigrations)) {
            $this->mysqli->begin_transaction();
            $this->mysqli->query($this->articles->putArticlesTable());
            $this->mysqli->query($this->news->putNewsTable());
            foreach ($this->users->putUsersTable() as $query) {
                $this->mysqli->query($query);
            }
            $this->mysqli->query($this->columnArticles->putColumnArticle());
            $this->mysqli->query($this->allThings->putAllThingsTable());
            $this->mysqli->query($this->brend->putBrendTable());
            $this->mysqli->query($this->chapter->putChapterTable());
            $this->mysqli->query($this->color->putColorTable());
            $this->mysqli->query($this->model->putModelTable());
            $this->mysqli->query($this->subchapter->putSubchapterTable());
            $this->mysqli->commit();
            return;
        }

        /**
         * Выполнение миграций, которые еще не выполнились
         */
        $differentMigrations = array_diff($arrMigration, $arrOfMigrations);
        foreach ($differentMigrations as $key => $value) {
            switch ($value) {
                case "Articles.php":
                    $this->mysqli->query($this->articles->putArticlesTable());
                    break;
                case "News.php":
                    $this->mysqli->query($this->news->putNewsTable());
                    break;
                case "Users.php":
                    $this->mysqli->begin_transaction();
                    foreach ($this->users->putUsersTable() as $query) {
                        $this->mysqli->query($query);
                    }
                    $this->mysqli->commit();
                    break;
                case "ColumnArticles.php":
                    $this->mysqli->query($this->columnArticles->putColumnArticle());
                    break;
                case "AllThings.php":
                    $this->mysqli->query($this->allThings->putAllThingsTable());
                    break;
                case "Brend.php":
                    $this->mysqli->query($this->brend->putBrendTable());
                    break;
                case "Chapter.php":
                    $this->mysqli->query($this->chapter->putChapterTable());
                    break;
                case "Color.php":
                    $this->mysqli->query($this->color->putColorTable());
                    break;
                case "Model.php":
                    $this->mysqli->query($this->model->putModelTable());
                    break;
                case "Subchapter.php":
                    $this->mysqli->query($this->subchapter->putSubchapterTable());
                    break;
            }
        }
    }
}

$migration = new Migrations();
$migration->migrations();