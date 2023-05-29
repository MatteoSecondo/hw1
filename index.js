function search(e)
{  
    e.preventDefault();

    if(!c_flag)
    {
        const results = document.querySelector('#results');
        results.classList.remove('hidden');
        results.classList.add('results');
        const location = encodeURIComponent(document.querySelector('#location').value);
        const categories = encodeURIComponent(document.querySelector('#categories').value);
        fetch('get_restaurant.php?location=' + location + '&categories=' + categories).then(onResponse).then(json => onJson(json, 0));
        fetch('get_restaurant_db.php?location=' + location + '&categories=' + categories).then(onResponse).then(json => onJson(json, 0));
    }
    
}

const search_restaurant = document.querySelector('.main-pages form');
search_restaurant.addEventListener('submit', search);

const categories = document.querySelector('#categories');
categories.addEventListener('input', () => {checkCategories(1)});