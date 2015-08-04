<?php
$appData = base64_decode($_REQUEST['APP_DATA']);
$ops = json_decode($appData);
?>

    window.cg = {
        CG_JQ_UI_THEME: <?php echo $_REQUEST['DEFAULT_THEME'] ?>,
        SSE_DELAY:<?php echo isset($ops->sse_delay)?$ops->sse_delay : 30000;?>
    }
    window.cgAsyncReady = function () {
        var appData = <?=$appData?>;
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
 		if ('<?php echo $_REQUEST['XDEBUG'] ?>' !== ''){
 			dev = dev + '&<?php echo $_REQUEST['XDEBUG'] ?>';
 		}
        var js = document.createElement("script");
        js.id = "cg-api";
        js.type = 'text/javascript';
        js.async = true;
        js.src = "<?php echo $_REQUEST['CG_SERVER'] ?>/server_api/s1/application/load/cg_api?app_id=<?php echo $_REQUEST['APP_ID'] ?>&RHZ_SESSION_ID=<?php echo $_REQUEST['SESSION_ID'] ?>&VERSION=<?php echo $_REQUEST['CG_VERSION'] ?>" + dev;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(js, s);
    }());
