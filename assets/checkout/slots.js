let collectionDays = document.querySelectorAll('.collection_day');
let collectionDaysArray = Array.prototype.slice.call(collectionDays);

let collectionTimes = document.querySelectorAll('.collection_time');
let collectionTimesArray = Array.prototype.slice.call(collectionTimes);



let selectCollectionDayCont = document.querySelector('.collection_day__container');
let selectCollectionTimeCont = document.querySelector('.collection_time__container');

//Wednesday = 3, Thursday = 4, Friday = 5
let defaultSlotDay = [3, 4, 5];

let defaultSlotTime = [
    {
        slot : [10, 13]
    },

    {
        slot : [13, 16]
    },

    {
        slot : [16, 19]
    }
];


let date = new Date();

let currentDay = date.getDay();
let currentHrs = date.getHours();

//Append element on select container
function appendDaysOnSlots(index, innerTxt) {
    //Creating option element
    let optionEl = document.createElement('option');
    collectionDaysArray[index].remove();
    optionEl.innerText = innerTxt;
    optionEl.setAttribute('class', 'collection_day');
    optionEl.setAttribute('value', innerTxt);

    selectCollectionDayCont.appendChild(optionEl);
}




function setTimeSlots() {

    defaultSlotDay.forEach((day) => {



        if(day - currentDay === 1 && (currentHrs >= 1 && currentHrs < defaultSlotTime[0].slot[1])) {

            if(currentDay === 3) {
                appendDaysOnSlots(0, 'Next Wednesday');
            }

            if(currentDay === 4) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
            }

        }
        
        if(day === 5 && currentDay === 5) {
            appendDaysOnSlots(0, 'Next Wednesday');
            appendDaysOnSlots(1, 'Next Thursday');
            appendDaysOnSlots(2, 'Next Friday');
        }


        if(day - currentDay === 1 && (currentHrs >= defaultSlotTime[1].slot[0] && currentHrs < defaultSlotTime[1].slot[1])) {

            if(currentDay === 2) {
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].selected = true;
            }

            if(currentDay === 3) {
                appendDaysOnSlots(0, 'Next Wednesday');
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].selected = true;
            }

            if(currentDay === 4) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].selected = true;
            }


            if(day === 5 && currentDay === 5) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                appendDaysOnSlots(2, 'Next Friday');
            }

        }



        if((day - currentDay === 1) && (currentHrs >= defaultSlotTime[2].slot[0] && currentHrs < defaultSlotTime[2].slot[1])) {

            if(currentDay === 2) {
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].disabled = true;
                collectionTimesArray[2].selected = true;
            }

            if(currentDay === 3){
                appendDaysOnSlots(0, 'Next Wednesday');
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].disabled = true;
                collectionTimesArray[2].selected = true;

            }

            if(currentDay === 4) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                collectionTimesArray[0].disabled = true;
                collectionTimesArray[1].disabled = true;
                collectionTimesArray[2].selected = true;
            }

            if(day === 5 && currentDay === 5) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                appendDaysOnSlots(2, 'Next Friday');
            }

        }


        if(day - currentDay === 1 && (currentHrs >= defaultSlotTime[2].slot[1] && currentHrs < 24 )) {

            if(currentDay === 2) {
                appendDaysOnSlots(0, 'Next Wednesday');
            }

            if(currentDay === 3) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday')
            }

            if(currentDay === 4) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                appendDaysOnSlots(2, 'Next Friday');
            }

            if(day === 5 && currentDay === 5) {
                appendDaysOnSlots(0, 'Next Wednesday');
                appendDaysOnSlots(1, 'Next Thursday');
                appendDaysOnSlots(2, 'Next Friday');
            }

        }

    })

}

window.addEventListener('load', setTimeSlots);



function onChangeSlots() {

    let selectedSlotDays = selectCollectionDayCont.value;

    if(currentDay === 3 && (selectedSlotDays !== 'Thursday')) {
        collectionTimesArray.forEach((timeOptions, idx) => {
            timeOptions.disabled = false;
        })
    }

    if(currentDay === 3 && (selectedSlotDays === 'Thursday')) {
        window.location.href = "https://localhost/website/project/assets/checkout/checkout.php";
    }

    if(currentDay === 4 &&(selectedSlotDays !== 'Friday')) {
        collectionTimesArray.forEach((timeOptions, idx) => {
            timeOptions.disabled = false;
        })
    }

    if(currentDay === 4 && (selectedSlotDays === 'Friday')) {
        window.location.href = "https://localhost/website/project/assets/checkout/checkout.php";
    }

}

selectCollectionDayCont.addEventListener('change', onChangeSlots);




