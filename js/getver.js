        $(document).ready(function() {
            $.get("http://typecho.wehao.ml", function(data) {
                console.log(data);
                $("#latest").text(data.ver);
            });

        });