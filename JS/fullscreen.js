const fullscreenBtn =
document.getElementById("fullscreenBtn");

fullscreenBtn.addEventListener("click", () => {

    if(player.requestFullscreen){

        player.requestFullscreen();
    }

});