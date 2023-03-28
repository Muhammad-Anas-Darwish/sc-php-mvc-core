<?php

namespace app\core\db;
use app\core\Model;
use app\core\Application;

abstract class DbModel extends Model {
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save() {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statment = self::prepare("INSERT INTO $tableName (".implode(', ', $attributes).")
                VALUES(".implode(',', $params).")"); 

        foreach ($attributes as $attribute) {
            $statment->bindValue(":$attribute", $this->{$attribute});
        }

        $statment->execute();
        return true;
    }

    public function findOne($where) {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

}