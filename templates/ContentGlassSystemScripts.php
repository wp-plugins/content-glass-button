    window.cg = {
        CG_JQ_UI_THEME: <?php echo $_REQUEST['DEFAULT_THEME'] ?>
    }
    window.cgAsyncReady = function () {
        var appData = <?php echo $_REQUEST['APP_DATA'] ?>;
        CGAPI.loadApp(appData, function onAppLoad(application) {
            application.init();
        });
    };
    (function (port) {
        var fjs = document.getElementsByTagName('script')[0];
        if (document.getElementById("cg-api")) {
            return;
        }
 		var dev = '<?php echo $_REQUEST['DEV_MODE'] ?>';
 		if (dev !== ''){
 			dev = '&' + dev;
 		}
 		if (<?php echo $_REQUEST['XDEBUG'] ?> !== ''){
 			dev = dev + '&<?php echo $_REQUEST['XDEBUG'] ?>';
 		}
        var js = document.createElement("script");
        js.id = "cg-api";
        js.type = 'text/javascript';
        js.async = true;
        js.src = "http:/<?php echo $_REQUEST['CG_SERVER'] ?>" + (port != null ? ":" + port : "")
                + "/server_api/s1/application/load/cg_api?app_id=<?php echo $_REQUEST['APP_ID'] ?>&access_token=<?php echo $_REQUEST['ACCESS_TOKEN'] ?>&RHZ_SESSION_ID=<?php echo $_REQUEST['SESSION_ID'] ?>&VERSION=<?php echo $_REQUEST['CG_VERSION'] ?>" + dev;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(js, s);
    }());
