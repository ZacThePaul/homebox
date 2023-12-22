class PodcastPlayer {

    // When the player is first opened
    constructor() {

        // Player element
        this.element = document.getElementById('podcast-popup');

        // Episode details
        this.episodeDuration;
        this.episodeArtwork;
        this.episodeTitle;
        this.currentlyPlayingElement;

        // Player child elements
        this.playerSource = document.getElementById('player-source');               // <audio> that is hidden by default
        this.playButton = document.getElementById('player-play');                   // The play button on the actual player
        this.rewindButton = document.getElementById('player-rewind');               // The rewind button
        this.forwardButton = document.getElementById('player-forward');             // The fast-forward button
        this.artwork = document.querySelector('.player-playing-now-artwork img');   // The podcast artwork displayed on the player
        this.numerator = document.getElementById('player-time-numer');              // Time elapsed in episode
        this.denominator = document.getElementById('player-time-denom');            // Total duration of episode

        this.episodePlayButtons = document.getElementsByClassName('single-podcast-episode-play');

        // Open up the actual player
        this.element.classList.remove('is_off');

    }

    grabEpisodeDetails() {
        
    }
}





const podcastPopup = document.getElementById('podcast-popup');

const playerSource = document.getElementById('player-source');
const playerPlayButton = document.getElementById('player-play');
const playerRewind = document.getElementById('player-rewind');
const playerForward = document.getElementById('player-forward');

const playerArtwork = document.querySelector('.player-playing-now-artwork img');

const playerNum = document.getElementById('player-time-numer');
const playerDen = document.getElementById('player-time-denom');

const onPagePlayButtons = document.getElementsByClassName('single-podcast-episode-play');

// This is the element of the single episode that is currently playing
let currentlyPlayingElement;

function togglePlayButton(parentElement, player, link) {

    parentElement.classList.remove('is_playing');
    parentElement.classList.add('is_paused');

    if (player.src != link) {
        player.src = link;
        for (let i = 0; i < onPagePlayButtons.length; i++) {
            onPagePlayButtons[i].classList.remove('is_playing');
            onPagePlayButtons[i].classList.add('is_paused');
        }
    }

    if (player.paused) {
        player.play();
        parentElement.classList.remove('is_paused');
        parentElement.classList.add('is_playing'); 

        playerPlayButton.classList.remove('is_paused');
        playerPlayButton.classList.add('is_playing');
    }
    else {
        player.pause();
        parentElement.classList.remove('is_playing');
        parentElement.classList.add('is_paused'); 

        playerPlayButton.classList.remove('is_playing');
        playerPlayButton.classList.add('is_paused'); 
    } 

    currentlyPlayingElement = parentElement;

}

function togglePodcast(player) {
    if (player.paused) {
        player.play();
        playerPlayButton.classList.remove('is_paused');
        playerPlayButton.classList.add('is_playing'); 

        currentlyPlayingElement.classList.remove('is_paused');
        currentlyPlayingElement.classList.add('is_playing');
    }
    else {
        player.pause();
        playerPlayButton.classList.remove('is_playing');
        playerPlayButton.classList.add('is_paused'); 

        currentlyPlayingElement.classList.remove('is_playing');
        currentlyPlayingElement.classList.add('is_paused'); 
    } 
}

// Listens for click of episode's play button
document.addEventListener('DOMContentLoaded', (event) => {

    for (let i = 0; i < onPagePlayButtons.length; i++) {
        let item = onPagePlayButtons[i];

        item.addEventListener('click', function(e) {
            e.preventDefault();
            podcastPopup.classList.remove('is_off');
            togglePlayButton(this, playerSource, item.href);
        })
    }
    
});

// Listens for click of miniplayer's play button
playerPlayButton.addEventListener('click', function() {
    togglePodcast(playerSource);
});

playerRewind.addEventListener('click', function() {
    playerSource.currentTime -= 10;
});

playerForward.addEventListener('click', function() {
    playerSource.currentTime += 45;
});

let podcrackTick = setInterval(function() {
    let seconds = Math.floor(playerSource.currentTime);
    let hours = Math.floor(seconds / 3600);
    let minutes = Math.floor((seconds - (hours * 3600)) / 60);
    let remainingSeconds = seconds - (hours * 3600) - (minutes * 60);

    let formattedTime;
    if (hours > 0) {
        formattedTime = hours + ":" + String(minutes).padStart(2, '0') + ":" + String(remainingSeconds).padStart(2, '0');
    } else {
        formattedTime = minutes + ":" + String(remainingSeconds).padStart(2, '0');
    }

    playerNum.textContent = formattedTime;
}, 1000);


playerSource.addEventListener('play', function(){
    navigator.mediaSession.metadata = new MediaMetadata({
        title: 'Pompeii',
        artist: 'Bastille',
        artwork: [
            {src: playerArtwork.src, sizes: '96x96', type: 'image/png'},
        ]
    });
})

document.addEventListener('keydown', function (e) {
    let searchInput = document.getElementById('podcrack-search');

    if (document.activeElement !== searchInput) {

        if (e.key === ' ') {
            e.preventDefault();
            togglePodcast(playerSource);
        }
        if (e.key === 'j') {
            e.preventDefault();
            playerSource.currentTime -= 10;
        }
        if (e.key === 'l') {
            e.preventDefault();
            playerSource.currentTime += 45;
        }

    }
});