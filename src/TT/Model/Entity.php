<?php

namespace TT\Model;

/**
 *
 * @author tt
 */
abstract class Entity {

    private function dataFieldExists($field) {
        return 'dataholder' == $field || array_key_exists($field, $this->dataholder);
    }

    /**
     * Get the entity fields
     * @return array
     */
    public function getData() {
        return $this->dataholder;
    }

    public static function getTableName() {
        return static::$tableName;
    }

    /**
     * set field
     * @param type $name
     * @param type $value
     * @return \TT\Model\Entity
     * @throws \Exception
     */
    public function __set($name, $value) {
        $field = strtolower($name);

        if (!$this->dataFieldExists($field)) {
            throw new \Exception(
            "can't set field '$field' for this entity.");
        }

        $mutator = "set" . ucfirst(strtolower($name));
        if (method_exists($this, $mutator) &&
                is_callable([$this, $mutator])) {
            $this->$mutator($value);
        } else {
            $this->dataholder[$field] = $value;
        }

        return $this;
    }

    /**
     * get field
     * @param type $name
     * @return type
     * @throws \Exception
     */
    public function __get($name) {
        $field = strtolower($name);

        if (!$this->dataFieldExists($field)) {
            throw new \Exception(
            "can't get field '$field' for this entity.");
        }

        $accessor = "get" . ucfirst(strtolower($name));
        return (method_exists($this, $accessor) &&
                is_callable([$this, $accessor])) ? $this->$accessor() : $this->dataholder[$field];
    }

}
