<?php

function View($str) {
    echo $str;
}
function Back($str) {
    exit( $str);
}
function ViewScript($str) {
    View('<script>'.$str.'</script>');
}
function BackScript($str) {
    exit('<script>'.$str.'</script>');
}

?>