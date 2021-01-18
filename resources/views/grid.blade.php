@extends('admin::layouts.content')

@section('button')
@includeWhen($actions, 'admin::partials.actions')
@endsection

@section('content')
<div class="layui-card">
    <div class="layui-card-body">
        <!-- 表单搜索 开始 -->
        @includeWhen($filters, 'admin::partials.filters')
        <!-- 表单搜索 结束 -->
        <form autocomplete="off" onsubmit="return false;" data-auto="true" method="get">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" value="resort" name="action">
            <table id="grid" lay-filter="data-table" class="layui-table" lay-data="{height:'full-10'}"></table>
            @includeWhen($batchActions, 'admin::partials.batch_actions')
        </form>
    </div>
</div>

<script>
    (function() {
        window.form.render();
        layui.use("table", function() {
            var table = layui.table;
            // 渲染
            table.render({
                elem: "#grid",
                url: "{{ $resultsUrl }}",
                page: true,
                limit: 20,
                limits: [20,50,100,200,300,500,1000],
                toolbar: true,
                defaultToolbar: ["filter", "exports", "print"],
                skin: "line",
                even: true,
                toolbar: "#batch-actions",
                id: "grid",
                cols: [<?php echo new_json_encode($columns); ?>],
                parseData: function(res) {
                    return {
                        "code": res.error_code,
                        "count": res.meta.total,
                        "msg": res.message,
                        "data": res.data
                    };
                },
                request: {
                    limitName: "per_page"
                },
                done: function(res, curr, count) {
                    
                }
            });

            // 过滤
            var $ = layui.$,
                filter = {
                    reload: function() {
                    	var filters = Object.create(null);
                        var inputs = $(".form-filters .filter-input");
                        
                        $.each(inputs, function(i, n) {
                        	filters['filter[' + $(n).attr('name') + ']'] = $(n).val();
                        });

                        table.reload("grid", {
                            page: {
                                curr: 1
                            },
                            where: filters
                        });
                    }
                };

            $(".form-filters .layui-btn").on("click", function() {
                var type = $(this).data("type");
                filter[type] ? filter[type].call(this) : "";
            });

            //批量事件
            table.on("toolbar(data-table)", function(obj) {
                var selected = [];
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case "delete":
                        checkStatus.data.forEach(function(e) {
                            selected.push(e.id.raw);
                        });
                        $.msg.confirm("确定要操作这些数据吗？", function() {
                            $.form.load("{{ route('admin.model.batch_delete', $modelName) }}", {
                                ids: selected.join(","),
                                _token: "{{csrf_token()}}"
                            }, "delete");
                        });
                        break;
                    case "update":
                        layer.msg("编辑");
                        break;
                };
            });

        });

        // switchable
        form.on("switch(switchable)", function(data) {
            var value = this.checked ? 1 : 0;
            token = $(this).attr("data-csrf");
            action = $(this).attr("data-action");
            field = $(this).attr("name") || $(this).attr("data-field");
            $.form.load(action, {
                field: field,
                value: value,
                _token: token
            }, "post");
        });
    })();
</script>
@endsection
