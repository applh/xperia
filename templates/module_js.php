<?php

// header js
header("Content-Type: application/javascript");
?>

console.log('MODULE JS PHP');

// test POST fetch KO for react
// let fd = new FormData();
// fd.append('action', 'xperia');
// fd.append('toto', 'module');
// let response = await fetch('/wp-admin/admin-ajax.php', {
//     method: 'POST',
//     body: fd
// });
// let json = await response.json();
// console.log(json);

export default {
    test ()
    {
        console.log('test');
    },
    async test_fetch() {
        console.log('test_fetch');
        // test POST fetch
        let fd = new FormData();
        fd.append('action', 'xperia');
        fd.append('toto', 'test');
        let response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: fd
        });
        let json = await response.json();
        console.log(json);

    }
}