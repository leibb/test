<div style="width: 600px; height:500px;padding: 20px;">
    <form id="edit-form">
        <input type="hidden" name="id" value="<%=id%>">

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>工号</label>
			<input type="text" class="form-control" name="number" value="<%=number%>">
		</div>

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>账户姓名</label>
			<input type="text" class="form-control" name="name" value="<%=name%>">
		</div>

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>账户昵称</label>
			<input type="text" class="form-control" name="nickname" value="<%=nickname%>">
		</div>

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>手机号码</label>
			<input type="text" class="form-control" name="phone" value="<%=phone%>">
		</div>

		<div class="form-group">
			<label for="title">邮箱地址</label>
			<input type="text" class="form-control" name="email" value="<%=email%>">
		</div>

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>角色</label>
            @foreach($roles as $role)
                <div class=" checkbox">
                    <label><input type="checkbox" value="{{$role['id']}}" name="role_ids[]" class="role" <%=_.indexOf(roles.role_ids, {{$role['id']}}) !=-1 ? 'checked="true"' :''%>>{{$role['role_name']}}
                    </label>
                </div>
            @endforeach
		</div>

		<div class="form-group">
			<label for="title"><span style="color:red">*</span>所在部门</label>
            <select class="form-control"  name="dep_id">
                <option value=''>选择部门</option>
                <?php foreach ($deps as $dep): ?>
                <option value = <?=$dep['id']?> <%=dep_id == <?=$dep['id']?> ?"selected='true'":''%> >
                    <?=$dep['dep_name']?>
                </option>
                <?php endforeach;?>
            </select>
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