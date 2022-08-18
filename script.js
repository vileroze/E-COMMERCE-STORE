
let allColumnImg = document.querySelectorAll(".column-img");
let allColumns = document.querySelectorAll(".column");

//Cloning Node Lists of image columns
let allColumnsArr = Array.prototype.slice.call(allColumns);

const clickHandler = (index, currentCol) => {

    //Finding image from image container when someone hovers in any image
    let currentEl = allColumnsArr.find((curr, id) => id == index);
    currentEl.classList.add("width-100");

    let remainingColumns = allColumnsArr.filter((currEl, id) => {
        return id != index;
    });

    remainingColumns.forEach((col) => {
        col.classList.add("width-0");
    });

    //Mouse Out Event
    currentCol.addEventListener("mouseout", () => {
        mouseHandler(index);
    });
};

const mouseHandler = (index) => {

    //Finding image when someone mouse out from image container
    let currentEl = allColumnsArr.find((curr, id) => id == index);
    currentEl.classList.remove("width-100");
    let remColumns = allColumnsArr.filter((currEl, id) => {
        return id != index;
    });
    remColumns.forEach((col) => {
        col.classList.remove("width-0");
    });
};


allColumnImg.forEach((currentCol, index) => {

    //Mouse in Event
    currentCol.addEventListener("mouseover", () => {
        clickHandler(index, currentCol);
        console.log(currentCol);
    });
});


