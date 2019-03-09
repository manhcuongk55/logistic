<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-2 col-md-offset-1 text-right">
            <select id="search-type" class="form-control">
                <option value="/home/go1688/?s=SEARCH_TERM">1688</option>
                <option value="https://s.taobao.com/search?q=SEARCH_TERM&imgfile=&js=1&stats_click=search_radio_all%3A1&initiative_id=staobaoz_20151209&ie=utf8&sort=sale-desc">TAOBAO</option>
                <option value="http://list.tmall.com/search_product.htm?q=SEARCH_TERM&type=p&vmarket=&spm=3.7396704.a2227oh.d100&from=mallfp..pc_1_searchbutton">TMALL</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Nhập từ khóa">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="doSearch()"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
    <hr/>
</div>

<script>
    $(function(){
        $("#search").typeahead({
            source: function(s,callback){
                $.getJSON("/home/search?s=" + s,function(data){
                    var arr = []
                    for(var cnWord in data){
                        var vnWord = data[cnWord]
                        arr.push({
                            vnWord: vnWord,
                            cnWord: cnWord,
                        })
                    }
                    callback(arr)
                })
            },
            displayText:function(item){
                return item.vnWord
            },
            updater:function(item){
                // console.log(s)
                return item.cnWord
            },
        })
    })

    function doRequest(path, params, method) {
        method = method || "post"; // Set method to post by default if not specified.

        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);
        form.setAttribute("target","_blank")

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function doSearch(){
        var keyword = $("#search").val()
        keyword = $.trim(keyword)
        if(!keyword){
            return
        }
        var urlFormat = $("#search-type").val()
        keyword = encodeURI(keyword)
        var url = urlFormat.replace("SEARCH_TERM",keyword)
        window.open(url)
    }
</script>