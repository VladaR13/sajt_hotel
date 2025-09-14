document.querySelector('#loginForm').addEventListener('submit', e=>{
    e.preventDefault();
    let form = new FormData(e.target);
    fetch('login.php',{
        method:'POST',
        body: form
    }).then(res=>res.json()).then(data=>{
        if(data.status=='ok'){
            window.location.href='index.php';
        } else {
            alert(data.msg);
        }
    });
});
