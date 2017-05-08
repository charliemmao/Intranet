<html>

<head>
<title>Open New Window</title>

<script language="JavaScript">
window.onerror = null
var msgWindow  = null

function notifyDetailWindow() {
    msgWindow = window.open("/php/oedetail.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

function closeNotifyWindow() {
    if (msgWindow != null) msgWindow.close()
}

var cancelList            = null
var can_already_submitted = false

function canNotifyWindow() {
    msgWindow = window.open("/php/ocsum.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

function canNotifyDetailWindow() {
    msgWindow = window.open("/php/oedetail.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}
</script>

</html>

