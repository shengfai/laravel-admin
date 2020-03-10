//使用自定义的插件pca
layui.use(['form', 'layedit', 'laydate', 'upload', "jquery", "pca"], function () {
    var $ = layui.$
        , form = layui.form
        , pca = layui.pca;

    pca.init('#province', '#city', '#area');

    //输入提示
    $("#address").bind('input propertychange', function () {
            var address = document.getElementById("address").value;
            if (address == "") {
                document.getElementById("addressTip").style.display = "none";
                return;
            }
            var html = '';
            var d_province = document.getElementById("province");
            var d_city = document.getElementById("city");
            var d_area = document.getElementById("area");

            if(d_province){
                var province = document.getElementById("province").value;
            }else{
                var province = '';
            }

            if(d_city){
                var city = document.getElementById("city").value;
            }else{
                var city = '';
            }

            if(d_area){
                var area = document.getElementById("area").value;
            }else{
                var area = '';
            }

            if (province == "全部") {
                province = '';
            }
            if (city == "全部") {
                city = '';
            }
            if (area == "全部") {
                area = '';
            }

            //查询关键字
            var keywords = province + city + area + address;

           // 请求高德接口
            $.ajax({
                type: "get",
                url: "https://restapi.amap.com/v3/place/text?parameters",
                data: {
                    "key": $(".lbs_amap_key").val(),
                    "keywords" : keywords
                },
                cache: false,
                async: false,
                dataType: "json",
                success: function (json) {
                    var data = json.pois;
                    for (var i = 0; i < data.length; i++) {
                        html += '<dd lay-value="" class="addressDd"><span class="gao-name">' + data[i].name + '</span><span style="color:#9c9a9a;font-size:5px;" class="gao-tip">' + data[i].address + '</span>' + '</dd><input type="hidden" class="location" value="'+  data[i].location+'">';
                    }
                }

            });
            document.getElementById("addressTip").innerHTML = html;
            var s = document.getElementById("addressTip").innerHTML;
            if (html == "") {
                document.getElementById("addressTip").style.display = "none";
            } else {
                document.getElementById("addressTip").style.display = "block";
            }

            var lis = document.getElementById("addressDetail").getElementsByTagName("dd");
            for (var i = 0; i < lis.length; i++) {
                if (lis[i].tagName == "DD") {
                    lis[i].onclick = (function ($k,$itm) {
                            return function () {
                                // 获取文本
                                document.getElementById("address").value = $(this).find(".gao-name").text();
                                // 获取经纬度
                                var coordinate = $(this).next().val().split(",");
                                // 获取经度
                                document.getElementById("longitude").value = coordinate[0];
                                // 获取维度
                                document.getElementById("latitude").value = coordinate[1];
                                // 隐藏下拉框
                                document.getElementById("addressTip").style.display = "none";

                                $(".own_address").val($(this).find("span").text());
                            }
                        }
                    )
                    (i);
                }
            }
        }
    );

});