<table class="layui-table layui-box modal-form-box" lay-even lay-skin="nob" style="width:96%;margin:18px auto;">
    <colgroup>
        <col width="200px">
        <col>
    </colgroup>
     <thead>
    <tr>
        <th><b>日志类型</b></th>
        <th>{{ $log->type_format }}</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>操作用户</b></td>
            <td>{{ $log->user_id }}</td>
        </tr>
        <tr>
            <td><b>操作IP</b></td>
            <td>{{ $log->ip }}</td>
        </tr>
        <tr>
            <td><b>请求节点</b></td>
            <td>{{ $log->node }}</td>
        </tr>
        <tr>
            <td><b>请求方法</b></td>
            <td>{{ $log->method }}</td>
        </tr>
        <tr>
            <td><b>UserAgent</b></td>
            <td>{{ $log->user_agent }}</td>
        </tr>
        <tr>
            <td><b>提交参数</b></td>
            <td>
            	<div class="inline-block text-top margin-right-5">
                    {{ $log->params }}
                </div>
            </td>
        </tr>
    </tbody>
</table>
