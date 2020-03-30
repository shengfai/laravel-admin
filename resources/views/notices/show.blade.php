<div class="layui-box layui-card"
	style="width: 96%; margin: 18px auto; box-shadow: none">
	<div class="layui-header"
		style="height: auto; line-height: 30px; padding: 10px; border-bottom: 1px solid #f6f6f6;">
		<h1 class="font-s16">{{ $notice->data['message'] }}</h1>
		<p>
			<span class="color-desc">{{ $notice->created_at }}</span>
		</p>
	</div>
	@if(isset($notice->data['body']))
	<div class="layui-card-body layui-text">{!! $notice->data['body'] !!}</div>
	@else
	<pre class="layui-code" lay-encode="true">@json($notice->data, JSON_PRETTY_PRINT);</pre>
	<script type="text/javascript">
    layui.use('code', function(){
    	layui.code({
    		about: false
    	});
    });
    </script>
	@endif
</div>
