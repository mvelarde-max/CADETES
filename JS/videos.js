const videos = [

    "VIDEOS/GralGuemes.mp4",
    "VIDEOS/GralBelgrano.mp4",
    "VIDEOS/video3.mp4",
    "VIDEOS/video4.mp4",
    "VIDEOS/video5.mp4",
    "VIDEOS/video6.mp4"
];

let currentVideo = 0;

const player =
document.getElementById("videoPlayer");

player.autoplay = true;

player.muted = false;

player.play();

player.addEventListener("ended", () => {

    currentVideo++;

    if(currentVideo >= videos.length){

        currentVideo = 0;
    }

    player.src = videos[currentVideo];

    player.load();

    player.play();

});