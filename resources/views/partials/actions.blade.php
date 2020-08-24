@foreach ($actions as $item)
<button {{$item['click']}}="{{ $item['action']($modelName) }}" data-title="{{ $item['title'] . $config->getOption('single') }}" class="layui-btn layui-btn-sm">{{ $item['title'] . $config->getOption('single') }}</button>
@endforeach