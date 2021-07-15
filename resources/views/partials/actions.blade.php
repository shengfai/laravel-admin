@foreach ($actions as $item)
<button {{$item['click']}}="{{ $item['action']($modelName) }}" data-title="{{ $item['title'] }}" class="layui-btn layui-btn-sm">{{ $item['title'] }}</button>
@endforeach