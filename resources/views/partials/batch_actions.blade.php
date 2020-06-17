<script type="text/html" id="batch-actions">
    <div class="layui-btn-container batch-actions">
        @foreach ($batchActions as $item)
        <input type="button" class="layui-btn layui-btn-primary" lay-event="{{$item['action_name']}}" value="{{$item['title']}}" />
        @endforeach
    </div>
</script>