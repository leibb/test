<div style="width: 600px; height:500px;padding: 20px;">
    <form id="edit-form">
        <input type="hidden" name="id" value="<%=id%>">

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>部门名称</label>
			<input type="text" class="form-control" name="dep_name" value="<%=dep_name%>">
		</div>

		<div class="form-group">
			<label for="title">上级人员</label>
			<select class="form-control"  name="parent_role_id">
			    <option value='' >选择上级人员</option>
				<?php foreach ($deps as $dep): ?>
                    <option value = <?=$dep['id']?> <%=parent_dep_id == <?=$dep['id']?> ?"selected='true'":''%> >
                        <?=$dep['dep_name']?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label for="title">部门描述</label>
            <input type="text" class="form-control" name="dep_des" value="<%=dep_des%>">
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