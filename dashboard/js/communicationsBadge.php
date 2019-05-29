<script>
$(document).ready(function () {
    $('#communicationsBadge').html("<?php echo $totalNewComms;?>");
    if ($('#communicationsBadge').html() == "0") {
        $('#communicationsNavLink').hide();
    }
});
</script>