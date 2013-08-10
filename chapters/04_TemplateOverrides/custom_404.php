<?php if (($this->error->getCode()) == '404') {
    header("HTTP/1.0 404 Not Found");
    echo "My Custom 404 page";
    exit;
}
?> 
