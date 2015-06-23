<table class="table">
    <tr>
        <td><a href="/inbox">Task List</a></td>
        <td><a href="/archive">Archive</a></td>
    </tr>
</table>
<?= $taskForm ?>
<table class="table">
    <tr>
        <th>id</th>
        <?php foreach ($columnList as $column): ?>
            <th>
                <?php if ($column == $sort): ?>
                    <a class="hBlue" href="<?= url(action() . "?sort=$column&direction=" . ('asc' == $direction ? 'desc' : 'asc')) ?>"><?= $column ?></a>
                    <span class="hBlue"><?php if ('asc' == $direction): ?>&uarr;<?php else: ?>&darr;<?php endif; ?></span>
                <?php else: ?>
                    <a href="<?= url(action() . "?sort=$column&direction=desc") ?>"><?= $column ?></a>
                <?php endif; ?>
            </th>
        <?php endforeach; ?>
        <th>actions</th>
    </tr>
    <?php foreach ($data as $row): ?>
        <?php
        if ($row['category'] != $category) {
            continue;
        }
        ?>
        <tr class="task-row <?php if (isDone($row['state'])): ?>
                done-row
            <?php elseif ($row['state'] == \TT\Model\Task::STATE_OVERDUE): ?>
                overdue-row
            <?php endif; ?>

            ">
            <td class="id field"><?= $row['id'] ?></td>
            <td class="title field"><?= $row['title'] ?></td>
            <td class="duedate field"><?= $row['duedate'] ?></td>
            <td class="priority field"><?= $row['priority'] ?></td>
            <td class="hidden state field"><?= $row['state'] ?></td>
            <td class="hidden category field"><?= $row['category'] ?></td>
            <td class="">
                <?php if (!isArchive($row['category'])): ?>
                    <button name="edit" class="hBlue" <?= isDone($row['state']) ? 'disabled="disabled"' : ''; ?>>edit</button>
                    <button name="archive" class="orange">archive</button>
                <?php endif; ?>
                <?php if (isArchive($row['category'])): ?>
                    <button name="delete" class="red">delete</button>
                <?php endif; ?>
            </td>
            <?php if (!isArchive($row['category'])): ?>
                <td class="">
                    <input class="done-tick" type="checkbox" <?= isDone($row['state']) ? 'checked="checked" disabled="disabled"' : ''; ?>>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>