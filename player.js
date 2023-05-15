//DEFINES

//video itself
const video = document.getElementById('video');
video.controls = false;

//radial buttons
const playButton = document.getElementById('playBtn');
const playIcon = document.getElementById('playIcon');
const pauseIcon = document.getElementById('pauseIcon');
const stopButton = document.getElementById('stopBtn');
const muteBtn = document.getElementById('muteBtn');
const muteSign = document.getElementById('muteSign');

//time elapsed and duration
const elapsed = document.getElementById('elapsed');
const duration = document.getElementById('duration');

//seekbar
const seekProgress = document.getElementById('seekProgress');
const seekHandle = document.getElementById('seekHandle');

//context menu
const contextMenu = document.getElementById('yorePlayContextMenu');
const contextAbout = document.getElementById('contextAbout');
const contextMute = document.getElementById('contextMute');
const contextLoop = document.getElementById('contextLoop');
const muteTick = document.getElementById('muteTick');
const loopTick = document.getElementById('loopTick');

//end screen stuff
const endScreen = document.getElementById('playerEndScreen');
const replayBtn = document.getElementById('endReplayBtn');

//the whole player
const scope = document.getElementById('playerBox');

//size controls
const shrinkBtn = document.getElementById('btnShrink');
const unshrinkBtn = document.getElementById('btnUnshrink');

//about box
const aboutBox = document.getElementById('aboutBox');
const aboutCloseBtn = document.getElementById('aboutCloseBtn');


//FUNCTIONS
//replace standart context menu and disallow standard right-click
scope.addEventListener("contextmenu", (event) => {
  event.preventDefault();

  const { clientX: mouseX, clientY: mouseY } = event;

  contextMenu.style.top = `${mouseY}px`;
  contextMenu.style.left = `${mouseX}px`;

  contextMenu.style.display = "block";
});

scope.addEventListener("click", (e) => {
  if (e.target.offsetParent != contextMenu) {
    contextMenu.style.display = "none";
  }
});

//play video
video.load()
video.play()

//play state toggle
function togglePlay() {
    if (video.paused || video.ended) {
      video.play();
      displayEndScreen();
      pauseIcon.style.display = "block";
      playIcon.style.display = "none";
    } else {
      video.pause();
      pauseIcon.style.display = "none";
      playIcon.style.display = "block";
    }
}

//time formatting
function formatTime(timeInSeconds) {
    const result = new Date(timeInSeconds * 1000).toISOString().substr(11, 8);
  
    return {
      minutes: result.substr(3, 2),
      seconds: result.substr(6, 2),
    };
  };

//stop video
function videoStop() {
  if (!video.ended) {
    video.pause();
    video.currentTime = 0;
    pauseIcon.style.display = "none";
    playIcon.style.display = "block";
  }  
}

//initializer
function initializeVideo() {
    const videoDuration = Math.round(video.duration);
    seekHandle.setAttribute('max', videoDuration);
    seekProgress.setAttribute('max', videoDuration);
    const time = formatTime(videoDuration);
    duration.innerText = `${time.minutes}:${time.seconds}`;
    duration.setAttribute('datetime', `${time.minutes}m ${time.seconds}s`)
    updateProgress();
}

//seek and skip
function skipAhead(event) {
  endScreen.style.display = "none";
  const skipTo = event.target.dataset.seekHandle ? event.target.dataset.seekHandle : event.target.value;
  video.currentTime = skipTo;
  seekProgress.value = skipTo;
  seekHandle.value = skipTo;
}

//update elapsed time counter
function updateElapsed() {
    const time = formatTime(Math.round(video.currentTime));
    elapsed.innerText = `${time.minutes}:${time.seconds}`;
    elapsed.setAttribute('datetime', `${time.minutes}m ${time.seconds}s`)
}

//update seekbar
function updateProgress() {
  seekHandle.value = Math.floor(video.currentTime);
  seekProgress.value = Math.floor(video.currentTime);
}

//detect autoplay
function detectAutoplay() {
    pauseIcon.style.display = "block";
    playIcon.style.display = "none";
}

//detect video end
function detectVideoEnd() {
    pauseIcon.style.display = "none";
    playIcon.style.display = "block";
    displayEndScreen();
}

//display and close about box
function displayAbout() {
  contextMenu.style.display = "none";
  aboutBox.style.display = "flex";
}

aboutCloseBtn.addEventListener('click', function() {
  aboutBox.style.display = "none";
})

//display end screen
function displayEndScreen() {
  if (!video.loop) {
    if (video.ended) {
    endScreen.style.display = "flex";
  }
  else {
    endScreen.style.display = "none";
  }
  seekHandle.value = 0;
  seekProgress.value = 0;
  }
  else {
    console.log("Video  set on loop. End screen not shown");
  }
}

//end screen replay button
function videoEndReplay() {
  video.currentTime = 0;
  video.play();
  displayEndScreen();
}

//shrink video
function videoShrink() {
  video.classList.toggle("playerVideoAreaSub");
  video.classList.toggle("playerVideoAreaSub2");
  shrinkBtn.style.display = "none";
  unshrinkBtn.style.display = "block";
}

//opposite to above
function videoUnshrink() {
  video.classList.toggle("playerVideoAreaSub");
  video.classList.toggle("playerVideoAreaSub2");
  unshrinkBtn.style.display = "none";
  shrinkBtn.style.display = "block";;
}

//mute button in control panel
function videoMute() {
  video.muted = !video.muted;
  muteSign.classList.toggle("muteSignHidden");
  muteTick.classList.toggle("tickInactive");

//TODO: volume control interactions
}

//mute option in context menu
function videoContextMute() {
  videoMute();
  contextMenu.style.display = "none";
}

//loop option, in same place
function videoContextLoop() {
  video.loop = !video.loop;
  loopTick.classList.toggle("tickInactive");
  contextMenu.style.display = "none";
}

//LISTENERS
//inits
video.addEventListener('loadedmetadata', initializeVideo);

//updaters
video.addEventListener('timeupdate', updateElapsed);
video.addEventListener('timeupdate', updateProgress);

//sensors
video.addEventListener('play', detectAutoplay);
video.addEventListener('ended', detectVideoEnd);

//playback control listeners
playButton.addEventListener('click', togglePlay);
stopButton.addEventListener('click', videoStop);
seekHandle.addEventListener('input', skipAhead);
replayBtn.addEventListener('click', videoEndReplay);

//volume control listeners
muteBtn.addEventListener('click', videoMute);

//size control listeners
shrinkBtn.addEventListener('click', videoShrink);
unshrinkBtn.addEventListener('click', videoUnshrink);

//context menu listeners
contextAbout.addEventListener('click', displayAbout);
contextMute.addEventListener('click', videoContextMute);
contextLoop.addEventListener('click', videoContextLoop);
