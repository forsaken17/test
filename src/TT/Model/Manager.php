<?php

namespace TT\Model;

use TT\Model\User,
    TT\Model\Bxbookrating,
    TT\Model\Bxbook,
    TT\Model\Bxuser;

/**
 * entity manager
 *
 * @author tt
 */
class Manager {

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function findBookRatingByCountry($country, array $sort = [], array $limit = []) {
        $tableBxbookrating = Bxbookrating::getTableName();
        $tableBxbook = Bxbook::getTableName();
        $tableBxuser = Bxuser::getTableName();

        $sqlLimit = '';
        if (!empty($limit['limit']) && array_key_exists('offset', $limit)) {
            $sqlLimit = ' limit ' . (!$limit['offset'] ? 0 : $limit['offset']) . ', ' . (!$limit['limit'] ? 10 : $limit['limit']);
        }
        $sqlSort = '';
        if (!empty($sort['order']) && array_key_exists('direction', $sort)) {
            $sqlSort = ' ORDER BY ' . $sort['order'] . ' ' . ('asc' == $sort['direction'] ? 'asc' : 'desc');
        }
        $where = "where un.Location like :search";
        $prepaired = [':search' => "%$country"];

        $sql = "select rt.ISBN, b.`Book-Title`, sum(rt.`Book-rating`) as rank
                from `$tableBxbookrating` as rt
                inner join `$tableBxuser` as un on rt.`User-ID`=un.`User-ID`
                inner join `$tableBxbook` as b on rt.`ISBN`=b.`ISBN`
                $where
                group by rt.ISBN
                $sqlSort
                $sqlLimit
                ";
        $sth = $this->db->prepare($sql);
        $sth->execute($prepaired);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $sth->fetchALL();

        if (!$data) {
            $data = [];
        }
        return $data;
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
            throw new \Exception('wrong credentials');
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

    public function find(Entity $entity) {
        $idVal = $entity->id;
        $idField = $entity->getMappedField('id');
        $tableName = $entity::getTableName();
        $sql = "SELECT * from `$tableName` where $idField = :id";
        $sth = $this->db->prepare($sql);
        $sth->execute([':id' => $idVal]);
        $data = $sth->fetch();
        if (!$data) {
            throw new \Exception("Entity with Id: $idVal not found");
        }
        $entityClass = get_class($entity);
        return new $entityClass($data);
    }

    public function save(Entity $entity) {
        $result = 0;
        if (empty($id = $entity->id)) {
            $result = $this->insert($entity);
        } else {
            $result = $this->update($entity);
        }
        return $result;
    }

    public function delete(Entity $entity) {
        $idVal = $entity->id;
        $idField = $entity->getMappedField('id');
        $sth = $this->db->prepare("delete from `{$entity::getTableName()}` where `$idField` = :id");
        $result = $sth->execute([':id' => $idVal]);
        return $result && $sth->rowCount();
    }

    public function insert(Entity $entity) {
        $entity->generateId();
        $data = $entity->getData();
        $table = $entity::getTableName();
        $fieldList = '(`' . join('`, `', array_keys($data)) . '`)';
        $keyList = array_keys($data);
        array_walk($keyList, function (&$val) {
            $val = str_replace(['-'], '', $val);
        });
        $valueList = '(:' . join(', :', $keyList) . ')';
        $sql = "INSERT INTO `$table` $fieldList value $valueList";
        $sth = $this->db->prepare($sql);

        $keyList = array_keys($data);
        array_walk($keyList, function (&$val) {
            $val = ':' . str_replace(['-'], '', $val);
        });
        $map = array_combine($keyList, $data);
        $result = $sth->execute($map);
        return $result && $sth->rowCount();
    }

    public function update(Entity $entity) {
        $data = $entity->getData();
        $table = $entity::getTableName();
        $prepared = [];
        foreach ($data as $key => $val) {
            if ($key !== 'id') {
                $param = str_replace(['-'], '', $key);
                $prepared["`$key`=:$param"] = $val;
            }
        }

        $fieldList = join(', ', array_keys($prepared));
        $idField = $entity->getMappedField('id');
        $sql = "update `$table` SET $fieldList where `$idField` = :id";
        $sth = $this->db->prepare($sql);

        $keyList = array_keys($data);
        array_walk($keyList, function (&$val) {
            $val = ':' . str_replace(['-'], '', $val);
        });
        $map = array_merge(array_combine($keyList, $data), [':id' => $entity->id]);
        $result = $sth->execute($map);
        return $result && $sth->rowCount();
    }

}
