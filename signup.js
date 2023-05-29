function onSubmit(e)
{
    if(e_flag || p_flag || pc_flag)
    {
        e.preventDefault();
    }
}

function onEmail(json)
 {
    const error = document.querySelector('#e_email');

    if(json == 'Exists')
    {
        error.classList.remove('hidden');
        error.classList.add('error');
        e_flag = true;
    }
    else
    {
        error.classList.remove('error');
        error.classList.add('hidden');
        e_flag = false;
    }
 }

 function onResponse(response)
 {
    return response.json();
 }
 
 function checkEmail()
 {
    fetch('check_email.php?email=' + encodeURIComponent(email.value)).then(onResponse).then(onEmail);
 }   

 function checkPassword()
 {
    const password_check = password.value.search(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$!%*?&]{8,16}$/);
    const error = document.querySelector('#e_password');
    const error2 = document.querySelector('#e2_password');

    if(password_check)
    {
        error.classList.remove('hidden');
        error.classList.add('error');
        error2.classList.remove('hidden');
        error2.classList.add('error');
        p_flag = true;
    }
    else
    {
        error.classList.remove('error');
        error.classList.add('hidden');
        error2.classList.remove('error');
        error2.classList.add('hidden');
        p_flag = false;
    }
    
 }   

 function checkPasswordConfirm()
 {
    const error = document.querySelector('#e_pass_confirm');

    if(password.value !== pass_confirm.value)
    {   
        error.classList.remove('hidden');
        error.classList.add('error');
        pc_flag = true;
    }
    else
    {
        error.classList.remove('error');
        error.classList.add('hidden');
        pc_flag = false;
    }
 }   

const email = document.querySelector('#email');
const password = document.querySelector('#password');
const pass_confirm = document.querySelector('#pass_confirm');

email.addEventListener('blur', checkEmail);
password.addEventListener('blur', checkPassword);
pass_confirm.addEventListener('input', checkPasswordConfirm);

const form = document.querySelector('.other-pages form');
form.addEventListener('submit', onSubmit);

let e_flag = false;
let p_flag = false;
let pc_flag = false;