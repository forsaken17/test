<?php

namespace TT\Model;

use TT\Locator;
use TT\Model\User;

/**
 * entity manager
 *
 * @author tt
 */
class Manager {

    public function __construct(Locator $sl) {
        $this->sl = $sl;
        $this->db = $sl->db;
    }

    /**
     *
     * @param type $email
     * @param type $sha1
     * @return User
     */
    public function findUserByEmailAndPassword($email, $sha1) {
        $tableName = User::getTableName();
        $sth = $this->db->prepare("SELECT id from {$tableName} "
                . "where email = :email and sha1 = :sha1");
        $sth->execute([':email' => $email, ':sha1' => $sha1]);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $sth->fetch();
        if (!$data) {
            throw new \Exception('user not found');
        }
        return new User($data);
    }

    public function findUserByEmail($email) {
        $tableName = User::getTableName();
        $sth = $this->db->prepare("SELECT id from {$tableName} where email = :email");
        $sth->execute([':email' => $email]);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $sth->fetch();
        if (!$data) {
            throw new \Exception('user not found');
        }
        return new User($data);
    }

    public function findTaskByParam(array $params, array $sort = []) {

        $sqlSort = '';
        if (!empty($sort['order']) && array_key_exists('direction', $sort)) {
            $sqlSort = ' ORDER BY ' . $sort['order'] . ' ' . ('asc' == $sort['direction'] ? 'asc' : 'desc');
        }
        $where = 'where ';
        $prepaired = [];
        foreach ($params as $operator => $condition) {
            $where .= (0 == $operator && !in_array((string) $operator, ['and', 'or'])) ? '' : $operator;
            foreach ($condition as $key => $val) {
                $where .= " $key = :$key ";
                $prepaired[":$key"] = $val;
            }
        }
        $tableName = Task::getTableName();

        $sql = "SELECT id, title, priority, duedate, state, category, user_id "
                . "from {$tableName} $where $sqlSort";
        $sth = $this->sl->db->prepare($sql);
        $sth->execute($prepaired);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $sth->fetchALL();

        if (!$data) {
            $data = [];
        }
        return $data;
    }

    public function findTaskById($id) {
        $tableName = Task::getTableName();
        $sth = $this->db->prepare("SELECT id, state from {$tableName} "
                . "where id = :id");
        $sth->execute([':id' => $id]);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $sth->fetch();
        if (!$data) {
            throw new \Exception('task not found');
        }
        return new Task($data);
    }

    public function save(Entity $entity) {
        $data = $entity->getData();
        if (empty($data['id'])) {
            $this->insert($data, $entity::getTableName());
        } else {
            $this->update($data, $entity::getTableName());
        }
    }

    public function delete($id, $table) {
        $sth = $this->db->prepare("delete from $table where id = :id");
        return $sth->execute([':id' => $id]);
    }

    public function insert($data, $table) {

        $fieldList = '(' . join(', ', array_keys($data)) . ')';
        $valueList = '(:' . join(', :', array_keys($data)) . ')';
        $sth = $this->db->prepare("INSERT INTO $table $fieldList "
                . " value $valueList");

        $keyList = array_keys($data);
        array_walk($keyList, function (&$val) {
            $val = ':' . $val;
        });
        $result = array_combine($keyList, $data);
        return $sth->execute($result);
    }

    public function update($data, $table) {

        $prepared = [];
        foreach ($data as $key => $val) {
            if ($key !== 'id') {
                $prepared["$key=:$key"] = $val;
            }
        }

        $fieldList = join(', ', array_keys($prepared));
        $sth = $this->db->prepare("update $table SET $fieldList "
                . "where id = :id");

        $keyList = array_keys($data);
        array_walk($keyList, function (&$val) {
            $val = ':' . $val;
        });
        $result = array_combine($keyList, $data);
        return $sth->execute($result);
    }

}
