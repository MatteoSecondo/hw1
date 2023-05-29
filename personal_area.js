function onDelete(json, err)
{
    if(json === 'Deleted')
    {
        err.parentNode.remove();
    }
    else
    {
        err.classList.remove('hidden');
        err.classList.add('error');
    }
}

function deleteRestaurant(e)
{
    e.preventDefault();
    const del_button = e.currentTarget;
    const err = del_button.previousElementSibling;
    const restaurant = err.parentNode;
    const name = restaurant.firstChild.textContent;
    fetch('delete_restaurant.php?name=' + name).then(onResponse).then(json => onDelete(json, err));
}

function insertOptions(json)
{
    //console.log(json);
    const country = document.querySelector('.country');
    const prefix = document.querySelector('.prefix');
    let option;

    for(let nat in json)
    {
        option = document.createElement('option');
        option.textContent = nat;
        option.value = nat;
        country.appendChild(option);

        option = document.createElement('option');
        option.textContent = json[nat].dialling_code;
        option.value = option.textContent;
        prefix.appendChild(option);
    }
}

function checkAddress()
{
    const address_check = address.value.search(/^[A-Za-z\d ]{1,50}\, [A-Za-z\d ]{1,20}?$/);
    const error = document.querySelector('#e_address');

    if(address_check)
    {
        error.classList.remove('hidden');
        error.classList.add('error');
        a_flag = true;
    }
    else
    {
        error.classList.remove('error');
        error.classList.add('hidden');
        a_flag = false;
    }
}

function onSubmit(e)
{
    const error1 = document.querySelector('#e1_img');
    if(img.src == 'http://localhost/webprogramming/hw1/images/add_restaurant.png')
    {
        error1.classList.remove('hidden');
        error1.classList.add('error');
        i_flag[0] = true;
    }

    if(a_flag || c_flag || i_flag[0] || i_flag[1] || i_flag[2])
    {
        e.preventDefault();
    }
}

function uploadValidation()
{
    const error1 = document.querySelector('#e1_img');
    error1.classList.remove('error');
    error1.classList.add('hidden');
    i_flag[0] = false;

    const size = upload.files[0].size;
    const ext = upload.files[0].name.split('.').pop();

    const error2 = document.querySelector('#e2_img');
    if (size >= 7000000) 
    {
        error2.classList.remove('hidden');
        error2.classList.add('error');
        i_flag[1] = true;
    } 
    else
    {
        error2.classList.remove('error');
        error2.classList.add('hidden');
        i_flag[1] = false;
    }
    
    const error3 = document.querySelector('#e3_img');
    if (!['jpeg', 'jpg', 'png', 'gif'].includes(ext))  
    {
        error3.classList.remove('hidden');
        error3.classList.add('error');
        i_flag[2] = true;
    } 
    else
    {
        error3.classList.remove('error');
        error3.classList.add('hidden');
        i_flag[2] = false;
    }

        
    if(!i_flag[0] || !i_flag[1] || !i_flag[2])
    {
        img.src = './images/' + upload.files[0].name;
    }
}

function onClick(e)
{
    e.preventDefault();
    upload.click();
}

fetch('search_favorites.php').then(onResponse).then(json => onJson(json, 0));
fetch('search_owners_restaurant.php').then(onResponse).then(json => onJson(json, 1));
fetch('search_countries.php').then(onResponse).then(insertOptions);

const form = document.querySelector('.restaurant form');
form.addEventListener('submit', onSubmit);

const img = document.querySelector('.start-upload');
img.addEventListener('click', onClick);

const upload = document.querySelector('#upload');
upload.addEventListener('change', uploadValidation);

const address = document.querySelector('#address');
address.addEventListener('blur', checkAddress);

const categories = document.querySelector('#categories');
categories.addEventListener('blur', () => {checkCategories(0)});




