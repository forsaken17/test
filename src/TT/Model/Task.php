<?php

namespace TT\Model;

/**
 *
 * @author tt
 */
class Task extends Entity {

    /**
     * states
     */
    const STATE_QUEUED = 1;
    const STATE_DONE = 2;
    const STATE_OVERDUE = 3;

    /**
     * categories
     */
    const CATEGORY_INBOX = 1;
    const CATEGORY_ARCHIVE = 2;
    const CATEGORY_TRASH = 3;

    protected static $tableName = 'task';
    protected $dataholder = [
        'id' => '',
        'title' => '',
        'priority' => '',
        'duedate' => '',
        'state' => '',
        'category' => '',
    ];

    public function __construct(array $data = null) {
        if (null !== $data) {
            $this->dataholder = $data;
        }
    }

    public function setUserId($param) {
        if ($param instanceof User) {
            $uid = $param->id;
        } else {
            $uid = $param;
        }
        $this->dataholder['user_id'] = $uid;
    }

    public function getUserId() {
        return $this->dataholder['user_id'];
    }

    public function setState($state) {
        if (!in_array($state, [Task::STATE_OVERDUE, Task::STATE_DONE, TASK::STATE_QUEUED])) {
            throw new Exception('incorrect state');
        }
        $this->dataholder['state'] = $state;
    }

    public function setCategory($category) {
        if (!in_array($category, [Task::CATEGORY_INBOX, Task::CATEGORY_ARCHIVE, TASK::CATEGORY_TRASH])) {
            throw new Exception('incorrect category');
        }
        $this->dataholder['category'] = $category;
    }

}
