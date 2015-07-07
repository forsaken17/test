<?php

namespace TT\Model;

/**
 *
 * @author tt
 */
abstract class Entity {

    protected $dataholder = [];
    protected $mapping = [];

    public function __construct(array $data = null) {
        if (null !== $data) {
            $this->dataholder = $data;
        }
    }

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
    public function generateId()
    {
        $this->id = str_shuffle(substr(md5(time()),0,10));
    }
    public static function getTableName() {
        return static::$tableName;
    }

    public function getMappedField($fieldName){
        return array_key_exists($fieldName, $this->mapping) ? $this->mapping[$fieldName] : $fieldName;
    }

    /**
     * set field
     * @param type $name
     * @param type $value
     * @return \TT\Model\Entity
     * @throws \Exception
     */
    public function __set($name, $value) {
        $field = $this->getMappedField(strtolower($name));

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
        $field = $this->getMappedField(strtolower($name));

        if (!$this->dataFieldExists($field)) {
            throw new \Exception(
            "can't get field '$field' for this entity.");
        }

        $accessor = "get" . ucfirst(strtolower($name));
        return (method_exists($this, $accessor) &&
                is_callable([$this, $accessor])) ? $this->$accessor() : $this->dataholder[$field];
    }

}
