
let reviewContainer = document.querySelector('.review-container');
let btnReview = document.querySelector('.btn-review');

btnReview.addEventListener('click', () => {
    reviewContainer.classList.toggle('show-review');
})


let allIcons = document.querySelectorAll('.review-star');
let ratingValue = document.querySelector('.rating-value');
let fasCount = 0;

allIcons.forEach((icon, index) => {
    icon.addEventListener('click', () => {

        let iconIndex = index;
        let iconClassName = icon.className;
        let splittedIconClassName = iconClassName.split(' ');

        if (splittedIconClassName[0] === 'fas') {

            for(let index = 4; index >= iconIndex; index-- ) {
                allIcons[index].removeAttribute('class');
                allIcons[index].setAttribute('class', 'far fa-star text-warning');
            }

            ratingValue.value = iconIndex;
        }


        if (splittedIconClassName[0] === 'far') {


            for(let index= 0; index <= iconIndex; index++) {
                allIcons[index].removeAttribute('class');
                allIcons[index].setAttribute('class', 'fas fa-star text-warning');
            }

            ratingValue.value = iconIndex + 1;

        }

    })
})