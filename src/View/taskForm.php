<form action="/edit" id="editForm" method="post">
    <input type="hidden" name="id" value="">
    <label for="title">Title</label>
    <input type="text" name="title" placeholder="New TODO"><br/>
    <label for="duedate">Due date</label>
    <input type="text" name="duedate" placeholder="<?= date('Y-m-d h:m') ?>"><br/>
    <label for="priority">Priority</label>
    <input type="number" name="priority" placeholder="1" min="1" max="3" step="1" value="1"><br/>
    <input type="hidden" name="state" placeholder="1" min="1" max="3" step="1" value="1">
    <input type="hidden" name="category" placeholder="1" min="1" max="3" step="1" value="1">
    <input type="submit" value="Save">
</form>