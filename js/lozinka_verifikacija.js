const inputPass = querySelector("#password");
const inputCheckpass = querySelector("#checkpassword");



addEventListener("input", (event) => {
    if(inputPass == inputCheckpass && !inputPass && !inputPass){
    inputPass.classList.Add("border-4 border-red-500");}
})


