function onSubmit(e)
{
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');

    if(!email.value || !password.value)
    {
        const error = document.createElement('span');
        error.textContent = 'Inserisci email e password';
        error.classList.add('error');
        password.after(error);

        e.preventDefault();
    }
}

const form = document.querySelector('.other-pages form');
form.addEventListener('submit', onSubmit);