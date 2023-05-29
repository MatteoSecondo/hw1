function insert_review(img_container)
{
    const textArea = document.querySelector('.review-content');
    const text = textArea.value;
    const rating = document.querySelector('.review-rating');

    const values = getValues(img_container);

    fetch('insert_review.php?text=' + text + '&review_rating=' + rating.value + '&name=' + values['name'] + '&src=' + values['src'] + '&categories=' + values['categories'] + '&address=' + values['address'] + '&city=' + values['city'] + '&country=' + values['country'] + '&phone=' + values['phone'] + '&price=' + values['price'] + '&review_count=' + values['review_count'] + '&rating=' + values['rating']).then(onResponse).then(onReview);
}

function onReview(json)
{
    //console.log(json);

    if(json !== '')
    {
        const reviews_container = document.querySelector('.reviews-container');

        for(let rev of json.reviews)
        {
            const rating = document.createElement('p');
            rating.textContent = rev.rating;

            const text = document.createElement('p');
            text.textContent = rev.text;

            const time_created = document.createElement('p');
            time_created.textContent = rev.time_created;

            const user = document.createElement('h3');
            user.textContent = rev.user.name;

            const review = document.createElement('div');
            review.classList.add('review');

            const bar = document.createElement('div');
            bar.classList.add('bar-review');

            review.appendChild(user);
            review.appendChild(rating);
            review.appendChild(text);
            review.appendChild(time_created);
            review.appendChild(bar);

            reviews_container.appendChild(review);
        }
    }

    
}

function onFavorite(json, heart)
{
    //console.log(json);
    
    if(json === 'Access denied')
    {
        alert("Devi effettuare l'accesso per poter aggiungere qualcosa ai preferiti!");
    }
    else if(json === 'Saved')
    {
        heart.src = 'images/favorite_heart_filled_icon.png';
        heart.removeEventListener('click', addFavorite);
        heart.addEventListener('click', removeFavorite);
    }
    else
    {
        heart.src = 'images/favorite_heart_empty_icon.png';
        heart.removeEventListener('click', removeFavorite);
        heart.addEventListener('click', addFavorite);
    }
}

function removeFavorite(e)
{
    e.stopPropagation();
    const heart = e.currentTarget;
    const img_container = heart.parentElement;
    const restaurant = img_container.parentElement;
    const name = encodeURIComponent(restaurant.firstChild.textContent);
    fetch('delete_favorites.php?name=' + name).then(onResponse).then(json => onFavorite(json, heart));
    const section = restaurant.parentNode;

    if(section.id === 'favorites')
    {
        restaurant.remove();
    }

    if(!section.children.length)
    {
        const error = document.createElement('span');
        error.textContent = "Nessun preferito";
        error.classList.add('error');
        error.style.fontSize = '3rem';
        error.style.margin = '0 auto';
        document.querySelector('.results').appendChild(error);
    }
    
}

function getValues(img_container)
{
    const restaurant = img_container.parentElement;
    
    const name = encodeURIComponent(restaurant.firstChild.textContent);
    const src = encodeURIComponent(img_container.firstChild.src);
    let category;
    let full_location;
    let phone;
    let price;
    let review_count;
    let rating;

    for (let child of restaurant.children)
    {

        switch (child.dataset.selector)
        {
            case 'category':
                category= child.textContent;
                break;
            case 'location':
                full_location = child.textContent;
                break;
            case 'phone':
                phone = child.textContent;
                break;
            case 'price':
                price = child.textContent;
                break;
            case 'review_count':
                review_count = child.textContent;
                break;
            case 'rating':
                rating = child.textContent;
                break;
        }     
    }

    category = category.substring(11);
    const categories = category.split(', ');
    categories.length -= 1;
    const formatted_categories = encodeURIComponent(JSON.stringify(categories));
    
    full_location = full_location.substring(11);
    const location = full_location.split(', ');
    const address = encodeURIComponent(location[0]);
    const city = encodeURIComponent(location[1]);
    const country = encodeURIComponent(location[2]);

    phone = encodeURIComponent(phone.substring(10));

    price = price.substring(8);
    price = encodeURIComponent(price.search(/$/));
    
    review_count = encodeURIComponent(review_count.substring(12));

    rating = encodeURIComponent(rating.substring(8));

    const values = {'name': name, 'src': src, 'categories': formatted_categories, 'address': address, 'city': city, 'country': country, 'phone': phone, 'price': price, 'review_count': review_count, 'rating': rating};

    return values;
}

function addFavorite(e)
{
    //prendere il div restaurant e prendere le info
    e.stopPropagation();
    const heart = e.currentTarget;
    const img_container = heart.parentElement;
    const restaurant = img_container.parentElement;
    
    const values = getValues(img_container);

    fetch('add_favorites.php?name=' + values['name'] + '&id=' + restaurant.dataset.id + '&src=' + values['src'] + '&categories=' + values['categories'] + '&address=' + values['address'] + '&city=' + values['city'] + '&country=' + values['country'] + '&phone=' + values['phone'] + '&price=' + values['price'] + '&review_count=' + values['review_count'] + '&rating=' + values['rating']).then(onResponse).then(json => onFavorite(json, heart));

}

function removeModal()
{
    document.body.classList.remove('no-scroll');
    modal.classList.add('hidden');
    modal.innerHTML = '';

    const header = document.querySelector('.header');
    header.style.display = 'flex';
}

function addModal(e)
{
    e.stopPropagation();
    const img = createImage(e.currentTarget.src);
    img.classList.add('pointer');
    document.body.classList.add('no-scroll');
    modal.style.top = window.scrollY + 'px';
    modal.appendChild(img);
    img.addEventListener('click', removeModal);

    const reviews = document.createElement('div');
    reviews.classList.add('reviews');

    const form = document.createElement('form');
    form.classList.add('insert-form');

    const img_container = e.currentTarget.parentElement;
    form.addEventListener('submit', (e) => {e.preventDefault(); insert_review(img_container)});

    const textArea = document.createElement('textarea');
    textArea.classList.add('review-content');
    textArea.placeholder = 'Qui puoi lasciare una recensione'

    const rating = document.createElement('input');
    rating.type = 'number';
    rating.min = 1;
    rating.max = 5;
    rating.placeholder = 'Stelle';
    rating.classList.add('review-rating');

    const submit = document.createElement('input');
    submit.type = 'submit';
    submit.classList.add('submit-review');

    form.appendChild(rating);
    form.appendChild(textArea);
    form.appendChild(submit);
    reviews.appendChild(form);
    
    const bar = document.createElement('div');
    bar.classList.add('bar');

    reviews.appendChild(bar);

    const reviews_container = document.createElement('div');
    reviews_container.classList.add('reviews-container');

    reviews.appendChild(reviews_container);
    modal.appendChild(reviews);

    const restaurant = img_container.parentElement;
    fetch('get_reviews.php?search=' + restaurant.dataset.id).then(onResponse).then(onReview);
    
    const name = encodeURIComponent(restaurant.firstChild.textContent);
    fetch('get_reviews_db.php?search=' + name).then(onResponse).then(onReview);

    modal.classList.remove('hidden');

    const header = document.querySelector('.header');
    header.style.display = 'none';
}

function checkCategories(mode)
{
    let categories_check;

    if(mode)
    {
        categories_check = categories.value.search(/^[A-Za-z ]{1,}\,[A-Za-z ]{0,}\,[A-Za-z ]{0,}?$/);
    }
    else
    {
        categories_check = categories.value.search(/^[A-Za-z ]{1,}\, [A-Za-z ]{0,}\, [A-Za-z ]{0,}?$/);
    }
    
    const error = document.querySelector('#e_categories');
    
    if(categories_check)
    {
        error.classList.remove('hidden');
        error.classList.add('error');
        c_flag = true;
    }
    else
    {
        error.classList.remove('error');
        error.classList.add('hidden');
        c_flag = false;
    }
}

function createImage(src)
{
    const img = document.createElement('img');
    img.src = src;
    return img;
}

function onJson(json, mode)
{
    //console.log(json);
    if(!mode)
    {
        counter++;
    }
    

    if(!mode && counter === 3)
    {
        document.querySelector('.results').innerHTML = '';
        counter = 0;
    }

    if(json === 'Nessun preferito')
    {
        const error = document.createElement('span');
        error.textContent = json;
        error.classList.add('error');
        error.style.fontSize = '3rem';
        error.style.margin = '0 auto';
        document.querySelector('.results').appendChild(error);
    }
    else if(json.error)
    {
        if(!mode)
        {
            e_counter++;
        }
        

        if(e_counter === 2)
        {
            const error = document.createElement('span');
            error.textContent = 'Nessun risultato';
            error.classList.add('error');
            error.style.fontSize = '3rem';
            error.style.margin = '0 auto';
            document.querySelector('.results').appendChild(error);
            e_counter = 0;
        }

    }
    else
    {
        //visualizzazione dei risultati
        for(let result of json.businesses)
        {
            let name = document.createElement('h1');
            name.textContent = result.name;
        
            let img = document.createElement('img');
            img.src = result.image_url;

            //questo serve per introdurre la modale
            img.addEventListener('click', addModal);

            //questo serve per introdurre i preferiti
            let img_container = document.createElement('div');
            img_container.classList.add('img-container');

            img_container.appendChild(img);

            //controllo se ci sono favoriti
            if(!mode)
            {
                let heart = createImage('');
                heart.classList.add('favorites');
                fetch('check_favorites.php?name=' + encodeURIComponent(name.textContent)).then(onResponse).then(json => onFavorite(json, heart));
                img_container.appendChild(heart);
            }

            let category = document.createElement('p');
            category.textContent = 'Categorie: '
            category.dataset.selector = 'category';

            for(let cat of result.categories)
            {
                category.textContent += cat.title + ', ';
            }

            let full_location = document.createElement('p');
            full_location.textContent = 'Indirizzo: ' + result.location.address1 + ', ' + result.location.city + ', ' + result.location.country;
            full_location.dataset.selector = 'location';
            
            let phone = document.createElement('p');
            phone.textContent = 'Telefono: ' + result.phone;
            phone.dataset.selector = 'phone';

            let price = document.createElement('p');
            price.textContent = 'Prezzo: ' + result.price;
            price.dataset.selector = 'price';

            let review_count = document.createElement('p');
            review_count.textContent = 'Recensioni: ' + result.review_count;
            review_count.dataset.selector = 'review_count';

            let rating = document.createElement('p');
            rating.textContent = 'Stelle: ' + result.rating;
            rating.dataset.selector = 'rating';

            let restaurant = document.createElement('div');
            restaurant.classList.add('restaurant'); 
            restaurant.dataset.id = result.id;

            restaurant.appendChild(name);
            restaurant.appendChild(img_container);
            restaurant.appendChild(full_location);
            restaurant.appendChild(phone);
            restaurant.appendChild(category);
            restaurant.appendChild(price);
            restaurant.appendChild(review_count);
            restaurant.appendChild(rating);

            let results;

            if(mode)
            {
                results = document.querySelector('#owners_restaurant');

                let err = document.createElement('span');
                err.classList.add('hidden');
                err.textContent = 'Cancellazione non riuscita';

                let del = document.createElement('button');
                del.classList.add('delete');
                del.textContent = 'Cancella';
                del.addEventListener('click', deleteRestaurant);
                
                restaurant.appendChild(err);
                restaurant.appendChild(del);
            }
            else
            {
                results = document.querySelector('.results');
            }

            results.appendChild(restaurant);
        }
    }
}

function onResponse(response)
{
    return response.json();
}

const modal = document.querySelector('#modal');
let e_counter = 0;
let counter = 0;

let a_flag = false;
let c_flag = false;
let i_flag = new Array();
