<div style="width: 600px; height:500px;padding: 20px;">
    <form id="edit-form">
        <input type="hidden" name="id" value="<%=id%>">
		<div class="form-group">
			<label for="title"><span style="color:red">*</span>权限名称</label>
			<input type="text" class="form-control" name="permission_name" value="<%=permission_name%>">
		</div>

        <div class="form-group">
            <label for="title"><span style="color:red">*</span>权限路径</label>
            <input type="text" class="form-control" name="permission_route" value="<%=permission_route%>">
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