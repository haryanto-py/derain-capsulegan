const dropAreaRain = document.getElementById('drop-area-rain');
const inputFileRain = document.getElementById('input-file-rain');
const imageViewRain = document.getElementById('img-view-rain');

const dropAreaTarget = document.getElementById('drop-area-target');
const inputFileTarget = document.getElementById('input-file-target');
const imageViewTarget = document.getElementById('img-view-target');

inputFileRain.addEventListener("change", uploadImageRain);

function uploadImageRain() {
    let imgLink = URL.createObjectURL(inputFileRain.files[0]);
    imageViewRain.style.backgroundImage = `url(${imgLink})`;
    imageViewRain.textContent = '';
    imageViewRain.style.border = 'none';
}

dropAreaRain.addEventListener("dragover", function(e){
    e.preventDefault();
});
dropAreaRain.addEventListener("drop", function(e){
    e.preventDefault();
    inputFileRain.files = e.dataTransfer.files;
    uploadImageRain();
});

inputFileTarget.addEventListener("change", uploadImageTarget);

function uploadImageTarget() {
    let imgLink = URL.createObjectURL(inputFileTarget.files[0]);
    imageViewTarget.style.backgroundImage = `url(${imgLink})`;
    imageViewTarget.textContent = '';
    imageViewTarget.style.border = 'none';
}

dropAreaTarget.addEventListener("dragover", function(e){
    e.preventDefault();
});
dropAreaTarget.addEventListener("drop", function(e){
    e.preventDefault();
    inputFileTarget.files = e.dataTransfer.files;
    uploadImageTarget();
});
