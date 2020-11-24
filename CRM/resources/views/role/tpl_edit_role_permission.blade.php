<div style="width: 600px; height:500px;padding: 20px;">
    <form id="edit-form">
        <input type="hidden" name="id" value="<%=id%>">

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>角色名称</label>
			<input type="text" class="form-control" name="role_name" value="<%=role_name%>">
		</div>

		<div class="form-group">
			<label for="title">上级人员</label>
			<select class="form-control"  name="parent_role_id">
				<?php foreach ($roles as $role): ?>
                    <option value = <?=$role['id']?> <%=parent_role_id == <?=$role['id']?> ?"selected='true'":''%> >
                        <?=$role['role_name']?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label for="title">角色描述</label>
            <input type="text" class="form-control" name="role_des" value="<%=role_des%>">
		</div>
    </form>
</div>

<style>
    .permission-group {
        display: none;
    }

    .big-title {
        font-size: 24px;
        padding: 10px 0;
        font-weight: bold;
        border-bottom: 1px solid #000;
        padding-bottom: 0;
    }
</style>