<div class="layui-form layui-form-pane form-search form-filters">
    @foreach ($filters as $filter)
    <div class="layui-inline">
        <label class="layui-form-label">{{ $filter['title'] }}</label>
        <div class="layui-input-inline">
            @if ($filter['type'] === 'key')
            <input class="layui-input filter-input" type="number" name="{{$filter['field_name']}}" data-type="{{$filter['type']}}" placeholder="检索{{$filter['title']}}">
            @elseif ($filter['type'] === 'text')
            <input class="layui-input filter-input" type="text" name="{{$filter['field_name']}}" data-type="{{$filter['type']}}" placeholder="检索{{$filter['title']}}">
            @elseif ($filter['type'] === 'enum')
            <select name="{{ $filter['field_name'] }}" class="layui-select filter-input" data-type="{{$filter['type']}}" lay-search="">
                @foreach ($filter['options'] as $option)
                <option value="{{$option['id']}}">{{$option['text']}}</option>
                @endforeach
            </select>
            @else

            @endif
        </div>
    </div>
    @endforeach
    <div class="layui-inline">
        <button class="layui-btn layui-btn-primary" data-type="reload"><i class="layui-icon">&#xe615;</i> 搜 索</button>
    </div>
</div>