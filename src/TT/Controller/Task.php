<?php

namespace TT\Controller;

use TT\Model\Task as TaskModel;

/**
 * 
 * @author tt
 */
class Task extends Front {

    public function inbox() {
        return $this->makeList(TaskModel::CATEGORY_INBOX);
    }

    public function archive() {
        return $this->makeList(TaskModel::CATEGORY_ARCHIVE);
    }

    public function delete() {
        if ($tid = filter_input(INPUT_POST, 'rowId', FILTER_SANITIZE_NUMBER_INT)) {
            $result = $this->dbm->delete($tid, TaskModel::getTableName());
            echo json_encode(['data' => $result, 'result' => 'success']);
        } else {
            \redirect(\url());
        }
    }

    public function edit() {
        $task = new TaskModel();
        $task->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $task->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $task->priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_NUMBER_INT);
        $task->duedate = filter_input(INPUT_POST, 'duedate', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_NUMBER_INT);
        $task->setState(!$state ? TaskModel::STATE_QUEUED : $state);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
        $task->setCategory(!$category ? TaskModel::CATEGORY_INBOX : $category);
        $task->setUserId($this->sl->auth->getSessionVar('uid'));
        $this->dbm->save($task);

        \redirect(\url());
    }

    public function changeCategory() {
        if ($task = $this->dbm->findTaskById(filter_input(INPUT_POST, 'rowId', FILTER_SANITIZE_NUMBER_INT))) {
            $task->setCategory(filter_input(INPUT_POST, 'rowCategory', FILTER_SANITIZE_NUMBER_INT));
            $this->dbm->save($task);
            echo json_encode(['data' => $task, 'result' => 'success']);
        } else {
            \redirect(\url());
        }
    }

    public function changeState() {
        if ($task = $this->dbm->findTaskById(filter_input(INPUT_POST, 'rowId', FILTER_SANITIZE_NUMBER_INT))) {
            $task->setState(filter_input(INPUT_POST, 'rowState', FILTER_SANITIZE_NUMBER_INT));
            $this->dbm->save($task);
            echo json_encode(['data' => $task, 'result' => 'success']);
        } else {
            \redirect(\url());
        }
    }

    private function makeList($category) {
        $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
        $direction = filter_input(INPUT_GET, 'direction', FILTER_SANITIZE_STRING);
        $sort = !$sort ? 'duedate' : $sort;

        $columnList = ['title', 'duedate', 'priority'];
        $key = array_search($sort, $columnList);
        $order = $columnList[$key];
        $uid = $this->sl->auth->getSessionVar('uid');

        $data = $this->dbm->findTaskByParam([['user_id' => $uid],
            'and' => ['category' => $category]], ['order' => $order, 'direction' => $direction]);

        return $this->sl->view->render('taskList', ['data' => $data, 'columnList' => $columnList,
                    'sort' => $sort, 'direction' => $direction,
                    'category' => $category,
                    'taskForm' => $this->sl->view->render('taskForm', [], true)]);
    }

}
